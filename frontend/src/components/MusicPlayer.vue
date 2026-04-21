<script lang="ts" setup>
import { ref } from 'vue'
import jp from 'jsonpath'

const props = defineProps<{
    list: string[];
}>()

type LyricLine = { [key: number]: string }
interface MusicInfo {
    id: string;
    name: string;
    artists: string[];
    cover: string;
    time: number;
    free?: {
        start: number;
        end: number;
    },
    url?: string;
    play_url?: string;
    lyric?: LyricLine[];
}

const baseAPI = 'https://api.stapxs.cn/music'
const musicList = ref<MusicInfo[]>([])
const nowPlaying = ref<MusicInfo | undefined>(undefined)

// 获取音乐基础信息
fetch(`${baseAPI}/song/detail?ids=${props.list.join(',')}`)
    .then(res => res.json())
    .then(data => {
        musicList.value = data.songs.map((song: any) => ({
            id: song.id,
            name: song.name,
            artists: jp.query(song, '$.ar[*].name'),
            cover: song.al.picUrl,
            time: song.dt / 1000
        }))
    })
    .then(() => {
        if (musicList.value.length > 0 && musicList.value[0]) {
            switchMusic(musicList.value[0])
        }
    })

const isLoaded = ref(true)
const playOnReady = ref(false)
const audio = ref<HTMLAudioElement | null>(null)

const sizeMax = ref(0)
const sizeReal = ref(0)
const minutes = ref(0)
const seconds = ref(0)

const sizeDur = ref(0)
const minutesDur = ref(0)
const secondsDur = ref(0)

const nowLyric = ref<{ index: number; text: string } | undefined>(undefined)

const lyricTime = (line: LyricLine | undefined) => parseFloat(Object.keys(line || {})[0] || '0')
const lyricText = (line: LyricLine | undefined) => Object.values(line || {})[0]

const audioLoaded = (event: any) => {
    isLoaded.value = true
    audio.value = event.target as HTMLAudioElement
    sizeMax.value = nowPlaying.value?.time ?? audio.value.duration
    sizeReal.value = audio.value.duration
    minutes.value = Math.floor(sizeMax.value / 60)
    seconds.value = Math.floor(sizeMax.value % 60)
    if(playOnReady.value && audio.value) {
        try {
            audio.value.play()
            playOnReady.value = false
            // 更新媒体信息
            if ('mediaSession' in navigator) {
                navigator.mediaSession.metadata = new MediaMetadata({
                    title : nowPlaying.value?.name ?? '',
                    artist: nowPlaying.value?.artists?.join('/') ?? '',
                    artwork: [
                        { src: nowPlaying.value?.cover ?? '', sizes: '130x130', type: 'image/png' },
                    ]
                });
            }
        } catch (e) {
            // 在用户首次交互前，音频是不会自动开始播放的。此处防止影响后续流程。
            // do nothing
        }
    }
    fetch(baseAPI + '/lyric?id=' + nowPlaying.value?.id)
        .then(res => res.json())
        .then(data => {
            const originalLyric = parseLyric(data.lrc?.lyric || '')
            const translatedLyric = parseLyric(data.tlyric?.lyric || '')
            // 默认展示“翻译 + 原文”合并结果：同时间点优先使用翻译歌词。
            nowPlaying.value!.lyric = mergeLyric(originalLyric, translatedLyric)
        })
}

const audioLoadFail = () => {
    // do nothing
}

const audioUpdate = () => {
    if(audio.value) {
        const bar = document.getElementById('music-controller-bar') as HTMLInputElement
        if(bar) {
            bar.value = audio.value.currentTime.toString()
        }
        sizeDur.value = audio.value.currentTime
        minutesDur.value = Math.floor(audio.value.currentTime / 60)
        secondsDur.value = Math.floor(audio.value.currentTime % 60)
        if(audio.value.currentTime >= audio.value.duration) {
            resetController()
        }
        if(nowPlaying.value?.lyric && nowPlaying.value.lyric.length > 0) {
            const lyricIndex = findLyricIndex(nowPlaying.value.lyric, audio.value.currentTime)
            if(lyricIndex >= 0) {
                const text = lyricText(nowPlaying.value.lyric[lyricIndex])
                if(!nowLyric.value || nowLyric.value.index !== lyricIndex || nowLyric.value.text !== text) {
                    nowLyric.value = {
                        index: lyricIndex,
                        text: text ?? '',
                    }
                }
            } else {
                nowLyric.value = undefined
            }
        }
    }
}

const audioChange = (event: any) => {
    const bar = event.target as HTMLInputElement
    if (audio.value) {
        const value = parseFloat(bar.value)
        if(value <= audio.value.duration) {
            if(audio.value.paused) {
                audio.value.currentTime = value
            } else {
                audio.value.pause()
                audio.value.currentTime = value
                audio.value.play()
            }
        } else {
            bar.value = audio.value.currentTime.toString()
        }
    }
}
const audioControll = () => {
    if (audio.value) {
        if (audio.value.paused) {
            audio.value.play()
        } else {
            audio.value.pause()
        }
    }
}

// =========================================

const resetController = () => {
    sizeMax.value = 0
    sizeReal.value = 0
    sizeDur.value = 0
    minutes.value = 0
    seconds.value = 0
    minutesDur.value = 0
    secondsDur.value = 0
    nowLyric.value = undefined
    const bar = document.getElementById('music-controller-bar') as HTMLInputElement
    if(bar) {
        bar.value = '0'
    }
}

const switchMusic = (music: MusicInfo, play: boolean = false) => {
    if(!isLoaded.value) {
        return
    }
    if(nowPlaying.value?.id === music.id) {
        audio.value?.play()
        return
    }
    isLoaded.value = false
    // 解析获取实际播放链接
        fetch(`${baseAPI}/song/url?id=${music.id}`)
            .then(res => res.json())
            .then(data => {
                music.play_url = data.data[0].url
            })
    nowPlaying.value = music
    playOnReady.value = play
}

const findLyricIndex = (lyrics: LyricLine[], currentTime: number) => {
    let left = 0
    let right = lyrics.length - 1
    let ans = -1
    while (left <= right) {
        const mid = Math.floor((left + right) / 2)
        const midTime = lyricTime(lyrics[mid])
        if (midTime <= currentTime) {
            ans = mid
            left = mid + 1
        } else {
            right = mid - 1
        }
    }
    return ans
}

const parseLyric = (lyricText: string) => {
    return lyricText
        .split('\n')
        .flatMap((line: string) => {
            const timeMatches = [...line.matchAll(/\[(\d{1,2}):(\d{1,2}(?:\.\d+)?)\]/g)]
            if (timeMatches.length === 0) {
                return []
            }
            const text = line.replace(/\[[^\]]+\]/g, '').trim()
            if (!text) {
                return []
            }
            return timeMatches.map(match => {
                const time = parseFloat(match[1] ?? '0') * 60 + parseFloat(match[2] ?? '0')
                return {
                    [time]: text,
                }
            })
        })
        .sort((a, b) => lyricTime(a) - lyricTime(b))
}

const mergeLyric = (originalLyric: LyricLine[], translatedLyric: LyricLine[]) => {
    const merged = new Map<number, string>()
    originalLyric.forEach(line => {
        merged.set(lyricTime(line), lyricText(line) ?? '')
    })
    translatedLyric.forEach(line => {
        merged.set(lyricTime(line), lyricText(line) ?? '')
    })
    return [...merged.entries()]
        .sort((a, b) => a[0] - b[0])
        .map(([time, text]) => ({ [time]: text }))
}
</script>

<template>
    <div class="music-player">
        <div class="controller">
            <img :src="nowPlaying?.cover" />
            <div>
                <span v-if="nowLyric?.text.trim() != ''">{{ nowLyric?.text }}</span>
                <audio :src="nowPlaying?.play_url ?? ''"
                    @loadedmetadata="audioLoaded"
                    @error="audioLoadFail"
                    @timeupdate="audioUpdate()" />
                <div>
                    <input id="music-controller-bar" value="0" min="0"
                        :max="sizeMax" step="0.1" type="range"
                        @input="audioChange">
                    <div>
                        <div :style="{
                            width: sizeDur / sizeMax * 100 > 100 ? 'calc(100% + 9px)' :('calc(' + (sizeDur / sizeMax * 100) + '% + 9px)'),
                        }" />
                        <div :style="{
                            width: sizeReal > 0 ? ('calc(' + ((1 - (sizeReal / sizeMax)) * 100) + '% - 9px)') : '0%',
                            marginLeft: sizeReal > 0 ? ('calc(' + ((sizeReal / sizeMax * 100) + '% + 9px)')) : '0%',
                        }" />
                    </div>
                    <font-awesome-icon v-if="!isLoaded" :icon="['fas', 'spinner']" spin />
                    <template v-else>
                        <font-awesome-icon v-if="audio?.paused" :icon="['fas', 'play']" @click="audioControll()" />
                        <font-awesome-icon v-else :icon="['fas', 'pause']" @click="audioControll()" />
                    </template>
                    <span>{{ minutesDur < 10 ? '0' + minutesDur : minutesDur }}:
                        {{ secondsDur < 10 ? '0' + secondsDur : secondsDur }} /
                        {{ minutes < 10 ? '0' + minutes : minutes }}:
                        {{ seconds < 10 ? '0' + seconds : seconds }}</span>
                </div>
            </div>
        </div>
        <div class="list">
            <div v-for="info in musicList" :key="info.id" @click="switchMusic(info, true)">
                <img :src="info.cover" />
                <div>
                    <a>{{ info.name }}</a>
                    <span>{{ info.artists.join(', ') }}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<style scoped>
.music-player {
    margin-top: 10px;
    display: flex;
}

.controller {
    background: var(--color-card-1);
    flex-direction: column;
    align-items: center;
    position: relative;
    margin-right: 10px;
    border-radius: 7px;
    overflow: hidden;
    display: flex;
    width: 45%;
}
.controller > img {
    border-radius: 7px;
    width: 100%;
}

.list {
    flex: 1;
}
.list > div {
    background: var(--color-card-1);
    border-left: 0 solid var(--color-main);
    transition: border 0.3s;
    align-items: center;
    border-radius: 7px;
    margin-bottom: 5px;
    cursor: pointer;
    padding: 10px;
    display: flex;
}
.list > div:hover {
    border-left: 5px solid var(--color-main);
}
.list > div > img {
    border-radius: 7px;
    margin-right: 10px;
    height: 40px;
    width: 40px;
}
.list > div > div {
    display: flex;
    flex-direction: column;
}
.list > div > div > a {
    margin-bottom: 4px;
    font-weight: bold;
}
.list > div > div > span {
    color: var(--color-font-1);
    font-size: 0.8rem;
}

.controller > div {
    background: rgba(var(--color-card-1-rgb), 0.8);
    backdrop-filter: blur(10px);
    width: calc(100% - 20px);
    position: absolute;
    padding: 10px;
    bottom: 0;
}
.controller > div > span {
    color: var(--color-font-2);
    text-align: center;
    font-size: 0.8rem;
    margin-bottom: 15px;
    display: block;
}
.controller > div > div {
    flex-direction: row;
    margin-top: 5px;
    flex-wrap: wrap;
    display: flex;
}
.controller > div > div > input {
    appearance: none;
    -webkit-appearance: none;
    width: 100%;
    background: transparent;
    margin-bottom: 10px;
    margin-right: 10px;
}
.controller > div > div > input::-webkit-slider-thumb {
    background: var(--color-main);
    -webkit-appearance: none;
    border-radius: 100%;
    margin-top: -3px;
    height: 12px;
    width: 12px;
}
.controller > div.me > div > input::-webkit-slider-thumb {
    background: var(--color-font-r);
}
.controller > div > div > input::-webkit-slider-runnable-track {
    background: var(--color-card-1);
    border-radius: 10px;
    height: 6px;
}
.controller > div.me > div > input::-webkit-slider-runnable-track {
    background: var(--color-font-2);
}
.controller > div > div > svg {
    font-size: 0.75rem;
    margin-left: 4px;
    margin-right: 10px;
    cursor: pointer;
}
.controller > div > div > span {
    font-size: 0.75rem;
    text-align: right;
    margin-right: 10px;
    flex: 1;
}
.controller > div > div > div {
    width: calc(100% - 20px);
    margin-bottom: -6px;
    margin-right: 20px;
    margin-left: 3px;
}
.controller > div > div > div > div {
    transform: translateY(calc(-100% - 10px));
    background: var(--color-main);
    pointer-events: none;
    border-radius: 6px;
    height: 6px;
    width: 0%;
}
.controller > div > div > div > div:nth-child(2) {
    transform: translateY(calc(-100% - 16px));
    background: var(--color-card-2);
    border-radius: 0 6px 6px 0;
}
.controller > div.me > div > div > div:nth-child(2) {
    background: var(--color-font-1);
}
.controller > div.me > div > div > div {
    background: var(--color-font-r);
}

.music-lyric {
    background: var(--color-card-1);
    display: block;
    text-align: center;
    padding: 5px;
    border-radius: 7px;
    margin-top: 10px;
    color: var(--color-font-2);
    font-size: 0.8rem;
}
</style>
