<script setup lang="ts">
import { onUnmounted, ref } from 'vue'
import type { Post } from '../types'
import MusicPlayer from '../components/MusicPlayer.vue'

defineProps({
    posts: {
        type: Array as () => Post[],
        required: true
    },
    image: {
        type: String,
        required: false,
        default: ''
    }
}) 

const ssAna = ref('')
fetch('https://api.stapxs.cn/text/ss-ana')
    .then(res => res.text())
    .then(data => {
        ssAna.value = data
    })

interface DeviceInfo {
    name: string[];
    icon: [string, string];
    data?: {
        icon?: string;
        name?: string;
        unit?: string;
        type: 'boolean' | 'value' | 'text';
        value: boolean | number | string;
    }[];
}

const deviceList = ref<DeviceInfo[]>([
    {
        name: ['MacBook Pro 14"', 'M2 Pro'],
        icon: ['fas', 'laptop'],
        data: [
            { icon: 'battery', name: 'sensor.lin_xiao_huai_de_macbook_pro_internal_battery_level', type: 'value', unit: '%', value: '-' }
        ]
    },
    {
        name: ['iPhone 14 Pro', 'BLACK'],
        icon: ['fas', 'mobile'],
        data: [
            { icon: 'light', name: 'lin_xiao_huai_de_iphone_14_pro_battery_state', type: 'boolean', value: false },
            { icon: 'volume-xmark', name: 'binary_sensor.lin_xiao_huai_de_iphone_14_pro_focus', type: 'boolean', value: false },
            { icon: 'battery', name: 'sensor.lin_xiao_huai_de_iphone_14_pro_battery_level', type: 'value', unit: '%', value: '-' }
        ]
    },
    {
        name: ['Apple Watch', 'Series 9 41mm'],
        icon: ['fab', 'apple'],
    },
    {
        name: ['Raspberry Pi 5', '4GB RAM'],
        icon: ['fas', 'server'],
        data: [
            { icon: 'memory', name: 'sensor.shu_mei_pai_5_nei_cun_zhan_yong', type: 'value', unit: '%', value: '-' },
            { icon: 'temperature-half', name: 'sensor.shu_mei_pai_5_cpu_wen_du', type: 'value', unit: '°C', value: '-' },
        ]
    },
    {
        name: ['Xbox Series S', 'Robot White'],
        icon: ['fab', 'xbox'],
        data: [
            { type: 'text', value: '卒于进水事件 😭' }
        ]
    },
    {
        name: ['NuPhy', 'Air60'],
        icon: ['fas', 'keyboard'],
         data: [
            { type: 'text', value: '好看的 60 配列万岁！' }
         ]
    }
])

const updateDeviceData = () => {
    deviceList.value.forEach(device => {
        if (device.data) {
            device.data.forEach(data => {
                fetch('https://api.stapxs.cn/inner/home/' + data.name)
                    .then(res => res.json())
                    .then(res => {
                        switch(data.type) {
                            case 'boolean':
                                const trueList = ['on', 'Charging']
                                data.value = trueList.indexOf(res.state) >= 0
                                break
                            case 'value':
                                data.value = res.state
                                break
                        }
                    })
            })
        }
    })
}
updateDeviceData()

// 为所有含有 name 的设备更新数据，每分钟更新一次
const time = setInterval(() => {
    updateDeviceData()
}, 60000)

onUnmounted(() => {
    clearInterval(time)
})

const skillList = [
    {
        name: ['Spring Boot', 'Java'],
        icon: ['fas', 'mug-hot'],
    },
    {
        name: ['Vue', 'TypeScript'],
        icon: ['fab', 'vuejs'],
    },
    {
        name: ['WPF', 'C Sharp'],
        icon: ['fas', 'hashtag'],
    },
    {
        name: ['Three.js', 'TypeScript'],
        icon: ['fab', 'typescript'],
    }
]

const musicIds = [
    '2037944267', '3342371458'
]
</script>

<template>
    <div class="home-view-inner">
        <div class="info">
            <div class="ss-card ab-base">
                <div id="ab-gravatar"><img src="https://secure.gravatar.com/avatar/75c55a45cadac8669a1c5e4943cda5ce?s=330&amp;r=G"></div>
                <p id="ab-name">林槐</p>
                <p id="ab-mail">stapx@outlook.com</p>
                <span id="ab-context">这是一只在摸鱼的 SS，咕噜噜噜噜 ……</span>
                <hr>
                <div id="ab-gpg">GPG Key：<a href="https://keys.openpgp.org/search?q=1A3579B5CF41188C">1A35 79B5 CF41 188C</a></div>
                <div class="social">
                    <a href="https://github.com/Stapxs" target="_blank"><font-awesome-icon :icon="['fab', 'github']" /></a>
                    <a href="https://o3o.ca/@stapxs" target="_blank"><font-awesome-icon :icon="['fab', 'mastodon']" /></a>
                    <a href="https://twitter.com/Stapx_Happy" target="_blank"><font-awesome-icon :icon="['fab', 'twitter']" /></a>
                    <a href="https://space.bilibili.com/12659126" target="_blank"><font-awesome-icon :icon="['fab', 'bilibili']" /></a>
                </div>
            </div>
            <div class="ss-card ab-image" :style="{ backgroundImage: 'url(' + image + ')' }">
                <div class="ab-image-cover">
                    <span>{{ ssAna }}</span>
                </div>
            </div>
        </div>
        <div class="ss-card">
            <header><div />碎碎念</header>
            <div class="ss-card notice-card">
                <font-awesome-icon :icon="['fas', 'road-circle-exclamation']" />
                <span>施工中</span>
            </div>
        </div>
        <div class="double-card">
            <div class="ss-card">
                <header><div />设备</header>
                <div class="device-list">
                    <div v-for="device in deviceList">
                        <font-awesome-icon :icon="device.icon" />
                        <div>
                            <a>{{ device.name[0] }}</a>
                            <span>{{ device.name[1] ?? '&nbsp;' }}</span>
                            <div v-if="device.data">
                                <div v-for="data in device.data">
                                    <span v-if="data.type == 'text'">{{ data.value }}</span>
                                    <font-awesome-icon v-else-if="data.type == 'boolean' && data.value" :icon="['fas', data.icon]" />
                                    <template v-else-if="data.type == 'value'">
                                        <font-awesome-icon :icon="['fas', data.icon]" />
                                        <span>{{ data.value }}</span>
                                        <span>{{ data.unit }}</span>
                                    </template>
                                </div>
                            </div>
                            <span v-else>&nbsp;</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ss-card">
                <header><div />技术栈</header>
                <div class="device-list">
                    <div v-for="skill in skillList">
                        <font-awesome-icon :icon="skill.icon" />
                        <div>
                            <a>{{ skill.name[0] }}</a>
                            <span>{{ skill.name[1] ?? '&nbsp;' }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="ss-card">
            <header><div />我的喜欢</header>
            <MusicPlayer :list="musicIds"/>
        </div>
        <div class="ss-card">
            <header><div />相簿</header>
            <div class="ss-card notice-card">
                <font-awesome-icon :icon="['fas', 'road-circle-exclamation']" />
                <span>施工中</span>
            </div>
        </div>
    </div>
</template>

<style scoped>
.home-view-inner {
    padding-top: 70px;
}
.double-card {
    display: flex;
}
.double-card > div {
    width: calc(calc(100% - 10px) / 2);
}
.double-card > div:first-child {
    margin-right: 10px;
}

.ss-card header {
    font-weight: normal;
    font-size: 0.9rem;
}
.ss-card header div:first-child {
    margin-right: 10px;
    height: 1rem;
}

.home-view-inner > div {
    margin-top: 10px;
}
.info {
    margin-top: 10px;
    display: flex;
}
.ab-card {
    margin-top: -100px;
    flex-direction: row;
    flex-wrap: wrap;
    display: flex;
    position: relative;
    z-index: 1;
}
.ab-card > div.says {
    flex: 1;
}

.ab-base {
    margin-right: 10px;
    display: flex;
    flex-direction: column;
    align-items: center;
    max-width: 30%;
    padding: 40px 20px;
}
.ab-base img {
    border-radius: 100%;
    transform: none;
    height: 60px;
    width: 60px;
    margin: 0;
}
.ab-base p#ab-name {
    color: var(--color-main);
    font-weight: bold;
    font-size: 1.2rem;
    margin-top: 10px;
    margin-bottom: 0;
}
.ab-base p#ab-mail {
    color: var(--color-font-2);
    font-size: 0.8rem;
}
.ab-base span#ab-context {
    font-size: 0.9rem;
    text-align: center;
}
.ab-base hr {
    border-top: 1px solid var(--color-card-2);
    height: 1px;
    width: 100%;
}
.ab-base > div#ab-gpg {
    color: var(--color-font-2);
    font-size: 0.9rem;
    display: flex;
}
.ab-base > div#ab-gpg a {
    background: var(--color-main);
    color: var(--color-font-r);
    border-radius: 100px;
    font-weight: bold;
    padding: 3px 10px;
    font-size: 0.7rem;
}

.social {
    display: flex;
    flex-direction: row;
    margin-top: 20px;
}
.social a {
    background: var(--color-card-1);
    border-radius: 100%;
    width: 35px;
    height: 35px;
    padding: 3px;
    margin-right: 5px;
    margin-left: 5px;
    display: flex;
    align-items: center;
    justify-content: center;
}
.social svg {
    color: var(--color-font-1);
    height: 15px;
    width: 15px;
}

.ab-image {
    position: relative;
    background-size: cover;
    background-position: center;
    flex: 1;
}
.ab-image-cover {
    background: rgba(var(--color-card-rgb), 0.3);
    flex-direction: column-reverse;
    position: absolute;
    overflow: hidden;
    display: flex;
    bottom: 0;
    right: 0;
    left: 0;
    top: 0;
}
.ab-image-cover span {
    background: rgba(var(--color-card-1-rgb), 0.7);
    color: var(--color-font-2);
    font-weight: bold;
    font-size: 0.9rem;
    display: block;
    padding: 10px;
    width: 100%;
}

.device-list {
    display: flex;
    flex-wrap: wrap;
}
.device-list > div {
    width: calc(calc(100% - 50px) / 2);
    background: var(--color-card-1);
    padding: 10px;
    margin-bottom: 10px;
    border-radius: 7px;
}
.device-list > div:nth-child(2n) {
    margin-left: 10px;
}
.device-list > div {
    display: flex;
    align-items: center;
}
.device-list > div > svg {
    color: var(--color-main);
    margin-right: 10px;
    font-size: 1.5rem;
}
.device-list > div > div > a {
    font-size: 0.9rem;
}
.device-list > div > div > span {
    color: var(--color-font-1);
    font-size: 0.8rem;
    display: block;
}
.device-list > div > div > div {
    display: flex;
}
.device-list > div > div > div svg {
    font-size: 0.75rem;
    margin-right: 5px;
}
.device-list > div > div > div span {
    color: var(--color-font-1);
    font-size: 0.75rem;
}
.device-list > div > div > div span:not(:first-child):not(:last-child) {
    margin-left: -2px;
}
</style>
