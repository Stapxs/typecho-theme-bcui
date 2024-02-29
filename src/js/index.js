document.addEventListener("DOMContentLoaded", () => {
    // 获取语录
    const anaBody = document.getElementById('ana')
    if (anaBody) {
        const url = anaBody.dataset.ana
        // 判断 url 是不是 http://、https:// 开头的
        if (url.indexOf('http://') === 0 || url.indexOf('https://') === 0) {
            fetch(anaBody.dataset.ana)
                .then(response => {
                    if (response.ok) {
                        return response.text()
                    }
                    return ''
                })
                .then(data => {
                    anaBody.innerText = data.replace(/[\r\n]/g, '')
                })
        } else {
            anaBody.innerText = url
        }
    }
    // 绑定一些事件
    document.getElementById('search-icon').addEventListener('click', () => {
        const search = document.getElementById('search-input')
        // 如果它有 hidden 就去掉，否则加回去
        if (search.classList.contains('hidden')) {
            search.classList.remove('hidden')
        } else {
            search.classList.add('hidden')
        }
    })
})

function showSearch() {
    
}

function jumpTo(url, newPage = false) {
    if(newPage) {
        window.open(url)
    } else {
        window.location.href = url
    }
}