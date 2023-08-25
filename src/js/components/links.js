// 获取正文中的所有 ul
const list = document.getElementById('main').getElementsByTagName('article')[0].getElementsByTagName('ul')
for(let i=0; i<list.length; i++) {
    // 处理 ul 内的 li
    const info = list[i].getElementsByTagName('li')
    const pan = document.createElement('div')
    pan.className = 'friend-pan'
    for(let j=0; j<info.length; j++) {
        let text = info[j].innerText
        if(text.indexOf('|') > 0) {
            text = text.split('|')
            // 将字符串处理为友链卡片
            // Chuhelan|https://www.chuhelan.com|/usr/uploads/2020/03/4234082537.jpg|这是初荷岚的个人博客，里面记载了一些无聊的东西
            /*
                <div id="pin-pan" class="pin">
                    <div class="ss-card">
                        <div class="pin-img" style="background: url()"></div>
                        <div>
                            <a href="/article/ss-ana">林槐语录</a>
                            <div><span>2021年05月这是01月到05月的，因为直接加进API了不记得具体日期了『人类的一切道德文明似乎都有虚伪的成分，但这恰恰是人和动物最大的一个区别。』——罗翔BV1Df4y1S7tB『就像你过去所做的</span></div>
                        </div>
                    </div>
                </div>
            */
           const card = document.createElement('div')
           card.className = 'pin friend-card'
           const main = document.createElement('div')
           main.className = 'ss-card'
           const info = document.createElement('div')
           const img = document.createElement('div')
           img.className = 'pin-img'
           img.style.background = 'url(' + text[2] + ')'
           const title = document.createElement('a')
           title.href = text[1]
           title.target = '_blank'
           title.innerText = text[0]
           const says = document.createElement('div')
           const says1 = document.createElement('span')
           says1.innerText = text[3]
           says.appendChild(says1)
           info.appendChild(title)
           info.appendChild(says)
           main.appendChild(img)
           main.appendChild(info)
           card.appendChild(main)
           pan.appendChild(card)
        }
    }
    // 处理 ul
    if(pan.children.length > 0) {
        const parent = list[i].parentNode
        parent.insertBefore(pan, list[i])
        list[i].style.display = 'none'
    }
}