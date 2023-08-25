let end = content.indexOf('=== end-about ===</code></pre>')
if(end > 0) {
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
        if(typeof item == 'object') {
            switch(item.type) {
                // 直接插入 HTML
                case 'HTML':  document.getElementById(name).innerHTML = item.value; break
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