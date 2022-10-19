window.onscroll = function() {
    // 处理顶栏
    const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    if(scrollTop > 80 && window.onView !== true) {
        changeBar()
        document.getElementById("main-bar").style.transform = "translate(0, -60px)"
        document.getElementById("article-bar").style.transform = "translate(0, -20px)"
        document.getElementById("article-bar").style.setProperty ("display", "flex", "important");
        document.getElementById("nav").style.borderBottom = "3px solid var(--color-main)";
        document.getElementById("nav-controller").style.display = "flex"
    } else if(scrollTop < 80 && window.onView === true) {
        changeBar()
        document.getElementById("main-bar").style.transform = "translate(0)"
        document.getElementById("article-bar").style.transform = "translate(0, 60px)"
        document.getElementById("article-bar").style.setProperty ("display", "none", "important");
        document.getElementById("nav").style.borderBottom = "3px solid transparent";
        document.getElementById("nav-controller").style.display = "none"
    }

    // 计算阅读进度
    const endHeight = getElementTop(document.getElementById("end-info"))
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

window.onload = function () {
    createTOC()
}

function barController() {
    const scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    if(scrollTop > 80) {
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
    console.log("正在处理目录 ……")
    // 检索 DOM
    const tags = [ 'H1', 'H2', 'H3', 'H4' ]
    const doms = document.getElementById('article-main').getElementsByTagName('*')
    let show = []
    for (i = 0; i < doms.length; i++) {
        const name = doms[i].nodeName
        if(tags.indexOf(name) >= 0) {
            show.push(doms[i])
        }
    }
    // 创建目录
    maxShow = 3         // 最大显示级别
    startLevel = 0
    lastLevel = 0
    let html = ''
    for (i = 0; i < show.length; i++) {
        const level = tags.indexOf(show[i].nodeName) + 1
        if(level > startLevel && level < maxShow) {
            if(level > lastLevel) {
                html += ''
            } else if (level < lastLevel) {
                html += (new Array(lastLevel - level + 2)).join("</ul></li>")
            } else {
                html += "</ul></li>"
            }
            html += "<li><a class=\"toc-level-" + level + "\" href=\"#toc-" + show[i].innerText + "\">" + show[i].innerText + "</a><ul>"
            lastLevel = level
        }
    }
    // 添加
    const body = document.createElement('div')
    body.className = 'content-body'
    body.innerHTML = html
    document.getElementById('content').append(body)
    // 创建进度条
    const bar = document.createElement('div')
    bar.className = 'content-progress'
    bar.id = 'content-progress'
    document.getElementById('content').append(bar)
    // 显示
    changeContent()
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