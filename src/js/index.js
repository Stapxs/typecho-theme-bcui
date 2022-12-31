window.onload = function () {
    // 获取语录
    const anaBody = document.getElementById('ana')
    if(anaBody && anaBody.dataset.ana !== '') {
        fetch(anaBody.dataset.ana)
        .then(response => {
            if(response.ok) {
                return response.text()
            }
            return ''
        })
        .then(data => {
            anaBody.innerText = data.replace(/[\r\n]/g, '')
        })
    }
}

function jumpTo(url, newPage = false) {
    if(newPage) {
        window.open(url)
    } else {
        window.location.href = url
    }
}