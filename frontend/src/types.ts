export type NodeParamType = 'input' | 'switch' | 'textarea' | 'number' | 'select' | 'settings' | 'condition'

export interface NodeParam {
    /** 参数 key */
    key: string
    /** 参数显示名称 */
    label: string
    /** 参数类型 */
    type: NodeParamType
    /** 是否固定显示在节点上（即使参数面板被隐藏） */
    pin?: boolean
    /** 默认值 */
    defaultValue?: any
    /** 是否必填 */
    required?: boolean
    /** 提示信息 */
    placeholder?: string,
    /** 说明文字 */
    tip?: string,
    /** 下拉选项（type 为 select 时使用） */
    options?: Array<{ label: string; value: any }>
    /** 是否支持动态值（从上游节点获取） */
    dynamic?: boolean,
    /** 可选：参数显示条件 */
    visibleWhen?: { key: string; value: any }
}

export interface Post {
    cid: string
    title: string
    description: string
    date: {
        timeStamp: number
        year: string
        month: string
        day: string
    }
    author: {
        name: string
        url: string
    }
    permalink: string
    tags: {
        name: string
        permalink: string
    }[]
    categories: {
        name: string
        permalink: string
    }[]
    commentsNum: number
}