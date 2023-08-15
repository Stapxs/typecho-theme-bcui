let viewer

document.addEventListener("DOMContentLoaded", () => {
    // 初始化图片查看器
    // 遍历获取文档中的所有图片
    const viewBody = document.getElementById('imageView')
    const doms = document.getElementById('article-main').getElementsByTagName('img')
    for(let i=0; i<doms.length; i++) {
        const item = doms[i]
        if(item.id != '') {
            const img = document.createElement('img')
            img.src = item.src
            viewBody.appendChild(img)
        }
    }
    viewer = new Viewer(viewBody, {
        button: false,
        toolbar: { prev: 1, reset: 1, next: 1 }
    })

    // 初始化目录并监听滚动
    createTOC()
    const list = document.getElementById('content-body').childNodes
    list.forEach((item) => {
        const dom = document.getElementById(item.dataset.id)
        if(dom !== null) {
            io.observe(document.getElementById(item.dataset.id))
        }
    })

    // 解析一些有必要的链接预览
    createLinkView()
})

// 图片点击事件
function viewImage(id) {
    viewer.view(Number(id.split('-')[1]))
}

// 页面滚动事件
window.onscroll = function() {
    // 处理顶栏
    const endHeight = getElementTop(document.getElementById("end-info"))
    const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    if(scrollTop >= 80 && scrollTop <= endHeight && window.onView !== true) {
        changeBar()
        document.getElementById("main-bar").style.transform = "translate(0, -60px)"
        document.getElementById("article-bar").classList.add('hid');
        document.getElementById("nav").style.borderBottom = "3px solid var(--color-main)";
        document.getElementById("nav-controller").style.display = "flex"
        // 在窄布局下显示目录
        // content.classList.remove('hidden')
    } else if((scrollTop < 80 || scrollTop > endHeight ) && window.onView === true) {
        changeBar()
        document.getElementById("main-bar").style.transform = "translate(0)"
        document.getElementById("article-bar").classList.remove('hid');
        document.getElementById("nav").style.borderBottom = "3px solid transparent";
        document.getElementById("nav-controller").style.display = "none"
        // 在窄布局下隐藏目录
        // content.classList.remove('show')
        // content.classList.add('hidden')
    }

    if(scrollTop >= 80 && scrollTop <= endHeight - window.screen.height) {
        // 在窄布局下显示目录
        content.classList.remove('hidden')
    } else {
        // 在窄布局下隐藏目录
        content.classList.remove('show')
        content.classList.add('hidden')
    }

    // 计算阅读进度
    const artTop = getElementTop(document.getElementById("article-main"))
    let percent = (scrollTop - artTop) / (endHeight - document.body.clientHeight)
    if(percent > 1) {
        percent = 1
    }
    if(percent < 0) {
        percent = 0
    }
    const progressBar = document.getElementById("content-progress")
    if(progressBar) {
        progressBar.style.width = "calc(calc(100% + 40px) * " + percent + ")"
    }
}

// H 标签自动亮起相关逻辑
let io = new IntersectionObserver(hInView, {
    rootMargin: '0px 0px -80% 0px'
})
let updateLock = false
function hInView (event) {
    const info = event[0]
    const sender = info.target
    // 进入
    if(info.isIntersecting) {
        sender.classList.add('select')
        if(!updateLock) {
            updateLock = true
            const list = document.getElementById('content-body').childNodes
            let passHidden = 0
            for(let i=0; i<list.length; i++) {
                const item = list[i]
                if(info.target.id === item.dataset.id) {
                    item.classList.add('select')
                    if(item.className.indexOf('lvt') >= 0) {
                        item.classList.remove('setsmall')
                        // H2 需要向前寻找它的 H1
                        // 同时将路过的 H2 全部展开
                        for(let j=i-1; j>=0; j--) {
                            if(list[j].className.indexOf('lvo') >= 0) {
                                list[j].classList.add('select')
                                break   // 找到了就可以退出了
                            } else {
                                list[j].classList.remove('setsmall')
                            }
                        }
                        // 向下寻找 H1 将路过的 H2 展开
                        for(let j=i+1; j<list.length; j++) {
                            if(list[j].className.indexOf('lvo') >= 0) {
                                break
                            } else {
                                list[j].classList.remove('setsmall')
                                // 统计个数以跳过下面的循环
                                passHidden ++
                            }
                        }
                    }
                } else {
                    item.classList.remove('select')
                    if(item.className.indexOf('lvt') >= 0) {
                        if(passHidden != 0) {
                            passHidden --
                        } else {
                            item.classList.add('setsmall')
                        }
                    }
                }
            }
            updateLock = false
        }
    } else {
        sender.classList.remove('select')
    }
}

function barController() {
    const endHeight = getElementTop(document.getElementById("end-info"))
    const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    if(scrollTop > 80 && scrollTop < endHeight) {
        changeBar()
        document.getElementById("nav").style.marginTop = "0"
    }
}

function changeBar() {
    if (window.onView === undefined || window.onView === false) {
        window.onView = true
        document.getElementById("nav").style.marginTop = "0"
        document.getElementById("nav").style.borderRadius = "0 0 7px 7px"
        document.getElementById("nav").style.transform = "translate(0, calc(-100% - 20px))"
        document.getElementById("nav-controller").style.margin = "30px auto 0"
        document.getElementById("nav-controller").style.opacity = "1"

        // document.getElementById("content").style.top = "20px"
    } else {
        window.onView = false
        document.getElementById("nav").style.marginTop = "20px"
        document.getElementById("nav").style.borderRadius = "7px"
        document.getElementById("nav").style.transform = "translate(0)"
        document.getElementById("nav-controller").style.margin = "-10px auto 0"
        document.getElementById("nav-controller").style.opacity = "0"

        // document.getElementById("content").style.top = "80px"
    }
}

function getElementTop (el) {
    let actualTop = el.offsetTop;
    let current = el.offsetParent;
    while (current !== null) {
        actualTop += current.offsetTop
        current = current.offsetParent
    }
    return actualTop
}

// 创建目录
function createTOC () {
    // 检索 DOM
    const tags = [ 'H1', 'H2', 'H3', 'H4', 'H5' ]
    const doms = document.getElementById('article-main').getElementsByTagName('*')
    let show = []
    let min = 6
    for (i = 0; i < doms.length; i++) {
        const name = doms[i].nodeName
        if(tags.indexOf(name) >= 0) {
            if(min > tags.indexOf(doms[i].nodeName) + 1) {
                min = tags.indexOf(doms[i].nodeName) + 1
            }
            show.push(doms[i])
        }
    }
    // 创建目录
    if(show.length > 0) {
        const div = document.createElement('div')
        div.id = 'content-body'
        div.className = 'content-body'
        // PS：用于记录上一个目录项的等级
        let last = 0
        for (i = 0; i < show.length; i++) {
            const item = show[i]
            const level = tags.indexOf(item.nodeName) - min + 2
            // 只构建两层
            if(level <= 2) {
                // 构建目录项
                const body = document.createElement('div')
                body.dataset.id = item.id
                const a = document.createElement('a')
                a.innerText = item.innerText
                a.href = '#' + item.id
                // PS：第一个如果不是 H1 就不显示，直到遇到第一个 H1
                if(last == 0 && level != 1) {
                    body.style.display = 'none'
                    break
                }
                if(level == 1) {
                    body.className = 'lvo'
                }
                if(level == 2) {
                    body.className = 'lvt setsmall'
                }
                last = level
                // 添加
                body.appendChild(a)
                div.appendChild(body)
            }
        }
        // 添加
        document.getElementById('content').append(div)
        // 创建进度条
        const bar = document.createElement('div')
        bar.className = 'content-progress'
        bar.id = 'content-progress'
        document.getElementById('content').append(bar)
        // 显示
        changeContent()
    }
}

function changeContent() {
    const body = document.getElementById("main-right")
    if(body.style.width === "0px") {
        body.style.width = "300px"
        body.style.overflow = "unset"
    } else {
        body.style.width = "0px"
        body.style.overflow = "hidden"
    }
}

function showContent() {
    const content = document.getElementById('content')
    if(window.getComputedStyle(content, null)['position'] == 'fixed') {
        if(content.classList.contains('show')) {
            content.classList.remove('show')
        } else {
            content.classList.add('show')
        }
    }
}

function createLinkView() {
    const doms = document.getElementById('article-main').getElementsByTagName('a')
    for (i = 0; i < doms.length; i++) {
        const item = doms[i]
        const link = item.href

        const regList = {
            github: /^https:\/\/github\.com\/[a-zA-Z0-9_-]+\/[a-zA-Z0-9_.-]+$/g,
            bilibili: /^https:\/\/www\.bilibili\.com\/video\/[a-zA-Z0-9_-]+$/g
        }

        for(regName in regList) {
            if(regList[regName].test(link)) {
                console.log('预览链接：' + link)
                loadView(regName, link, item)
            }
        }
    }
}