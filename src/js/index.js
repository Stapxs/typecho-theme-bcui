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
})

function jumpTo(url, newPage = false) {
    if(newPage) {
        window.open(url)
    } else {
        window.location.href = url
    }
}