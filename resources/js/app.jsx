import '../css/app.css'
import './bootstrap'
import { createInertiaApp } from '@inertiajs/react'
import { createRoot } from 'react-dom/client'

createInertiaApp({
    resolve: name => {
        const pages = import.meta.glob('./Pages/**/*.jsx', { eager: true })
        const directMatch = pages[`./Pages/${name}.jsx`]
        if (directMatch) return directMatch

        const targetPath = `./Pages/${name}.jsx`.toLowerCase()
        const caseInsensitivePath = Object.keys(pages).find(path => path.toLowerCase() === targetPath)
        if (caseInsensitivePath) return pages[caseInsensitivePath]

        throw new Error(`Inertia page component not found: ${name}`)
    },
    setup({ el, App, props }) {
        createRoot(el).render(<App {...props} />)
    },
})
