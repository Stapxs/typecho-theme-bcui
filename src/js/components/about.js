let end = content.indexOf('=== end-about ===</code></pre>')
if (end > 0) {
    end += `=== end-about ===</code></pre>`.length
    // 将正文内容插入到 content 后面
    const contentDom = document.createElement('div')
    contentDom.innerHTML = content.substring(end)
    document.getElementById('about-content').appendChild(contentDom)
    // 处理 info
    let info = content.substring(0, end).replace(/<[^>]*>/g, '').replace(`=== end-about ===`, '')
    const txt = document.createElement('div')
    txt.innerHTML = info
    info = JSON.parse(txt.innerText)
    console.log(info)
    // 将 info 填入对应 id 的 dom 中
    for (var name in info) {
        const item = info[name]
        if (typeof item == 'object') {
            switch (item.type) {
                // 直接插入 HTML
                case 'HTML': document.getElementById(name).innerHTML = item.value; break
                // 将 id 为 value 的 dom 挪到这儿
                case 'Context': {
                    const move = document.getElementById(item.value)
                    document.getElementById(name).innerText = ''
                    document.getElementById(name).appendChild(move)
                    break
                }
            }
        } else {
            document.getElementById(name).innerText = item
        }
    }
} else {
    console.log('没有在正文中找到关于信息 ……')
}

// 加载设备信息
const devices = document.getElementsByClassName('device-info')
const api = 'https://api.stapxs.cn/inner/home/'
for (i = 0; i < devices.length; i++) {
    const device = devices[i]
    const device_id = device.dataset.id
    const device_value = device.dataset.value
    const data_replace = device.dataset.replace
    const device_unit = device.dataset.unit
    const false_value = device.dataset.false
    const hidden = device.dataset.hidden
    if (device_id && device_value) {
        fetch(api + device_id)
            .then(res => res.json())
            .then(res => {
                const children = device.children
                if (children.length > 0) {
                    let value = res[device_value]
                    if(value === undefined) {
                        value = res['attributes'][device_value]
                    }
                    // 判断隐藏条件
                    if(hidden != undefined) {
                        const reg = new RegExp(hidden)
                        if(reg.test(value) || hidden == value) {
                            device.style.display = 'none'
                        }
                    }
                    // 存在文本需要显示
                    if(children.length == 2 && children[1].nodeName.toUpperCase() === 'SPAN') {
                        if(data_replace != undefined) {
                            const replace = data_replace.split("|")
                            if(replace.length == 2) {
                                // replace[0] 有可能是正则表达式
                                const reg = new RegExp(replace[0])
                                if(reg.test(value)) {
                                    value = value.replace(reg, replace[1])
                                } else {
                                    value = value.replace(replace[0], replace[1])
                                }
                            }
                        }
                        if(!isNaN(Number(value, 10))) {
                            value = Math.round(Number(value))
                        }
                        children[1].innerText = value + device_unit
                    }
                    // 状态切换显示
                    if(children.length == 1 && children[0].nodeName.toUpperCase() === 'SVG') {
                        if(false_value != undefined) {
                            if(value == false_value) device.classList.add('off')
                            else device.classList.add('on')
                        } else if (value) {
                            device.classList.add('on')
                        } else {
                            device.classList.add('off')
                        }
                    }
                }
            })
    }
}