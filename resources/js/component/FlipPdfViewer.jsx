import { useEffect, useMemo, useRef } from 'react'

export default function FlipPdfViewer({ src, options, className = '', style }) {
  const containerRef = useRef(null)
  const bookRef = useRef(null)

  const normalizedSrc = useMemo(() => {
    if (!src) return ''
    try {
      return encodeURI(src)
    } catch {
      return src
    }
  }, [src])

  useEffect(() => {
    const container = containerRef.current
    if (!container || !normalizedSrc) return

    const $ = window.jQuery
    if (!$?.fn?.flipBook) return
    const DFLIP = window.DFLIP
    const rootUrlInput = document.querySelector('#root_url')
    const rootUrl = (rootUrlInput?.value || window.location.origin || '').replace(/\/+$/, '')
    const libsBase = `${rootUrl}/vendor/flipjs/js/libs/`

    // This dflip build already computes library URLs from #root_url.
    // If dFlipLocation is set, it can double-prefix absolute URLs and cause 404s.
    try {
      if (Object.prototype.hasOwnProperty.call(window, 'dFlipLocation')) {
        delete window.dFlipLocation
      }
    } catch {
      window.dFlipLocation = undefined
    }

    if (DFLIP?.defaults) {
      DFLIP.defaults.mockupjsSrc = `${libsBase}mockup.min.js`
      DFLIP.defaults.pdfjsSrc = `${libsBase}pdf.min.js`
      DFLIP.defaults.pdfjsCompatibilitySrc = `${libsBase}compatibility.js`
      DFLIP.defaults.threejsSrc = `${libsBase}three.min.js`
      DFLIP.defaults.pdfjsWorkerSrc = `${libsBase}pdf.worker.min.js`
      DFLIP.defaults.cMapUrl = `${libsBase}cmaps/`
      DFLIP.defaults.soundFile = `${rootUrl}/vendor/flipjs/sound/turn2.mp3`
      DFLIP.defaults.imagesLocation = `${rootUrl}/vendor/flipjs/images`
      DFLIP.defaults.imageResourcesPath = `${rootUrl}/vendor/flipjs/images/pdfjs/`
    }

    if (bookRef.current?.dispose) {
      try {
        bookRef.current.dispose()
      } catch {
        // ignore
      }
      bookRef.current = null
    }

    container.innerHTML = ''

    const resolvedOptions = { ...(options || {}) }

    // Normalize pageMode so "double" really becomes DOUBLE mode.
    if (typeof resolvedOptions.pageMode === 'string') {
      const value = resolvedOptions.pageMode.trim().toLowerCase()
      if (value === 'double' || value === 'dbl' || value === 'doublepage') {
        resolvedOptions.pageMode = DFLIP?.PAGE_MODE?.DOUBLE ?? 2
      } else if (value === 'single' || value === 'sgl' || value === 'singlepage') {
        resolvedOptions.pageMode = DFLIP?.PAGE_MODE?.SINGLE ?? 1
      } else if (value === 'auto') {
        resolvedOptions.pageMode = DFLIP?.PAGE_MODE?.AUTO ?? null
      }
    }

    if (resolvedOptions.pageMode === undefined) {
      resolvedOptions.pageMode = DFLIP?.PAGE_MODE?.DOUBLE ?? 2
    }

    if (resolvedOptions.soundEnable === undefined) {
      resolvedOptions.soundEnable = false
    }

    bookRef.current = $(container).flipBook(normalizedSrc, {
      height: 'auto',
      webgl: true,
      ...resolvedOptions,
    })

    return () => {
      if (bookRef.current?.dispose) {
        try {
          bookRef.current.dispose()
        } catch {
          // ignore
        }
        bookRef.current = null
      }
      if (container) container.innerHTML = ''
    }
  }, [normalizedSrc, options])

  return <div ref={containerRef} className={`w-full ${className}`.trim()} style={style} />
}
