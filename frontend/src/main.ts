import type { Post } from './types'

import { createApp } from 'vue'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { library } from '@fortawesome/fontawesome-svg-core'
import { fas } from '@fortawesome/free-solid-svg-icons'
import { fab } from '@fortawesome/free-brands-svg-icons'

import './assets/css/style.css'

import HomeView from './views/HomeView.vue'
import NavBar from './components/NavBar.vue'
import ListView from './views/ListView.vue'

library.add(fas)
library.add(fab as any)

function safeMount<T>(id: string, component: any, props?: T) {
    const el = document.getElementById(id)
    if (!el) return
    try {
        const app = createApp(component, props as any)
        app.component('FontAwesomeIcon', FontAwesomeIcon)
        app.mount(el)
    } catch (e) {
        console.error(`挂载失败: ${id}`, e)
    }
}

safeMount<any>('header-view', NavBar, {
    title: (window as any).__TITLE__ as string
})
safeMount<any>('home-view', HomeView, {
    image: (window as any).__IMAGE__ as string || ''
})
safeMount<any>('list-view', ListView, {
    posts: (window as any).__POSTS__ as Post[] || []
})