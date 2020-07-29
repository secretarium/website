import { AppProps } from 'next/app'
import Router from 'next/router';
import NProgress from 'nprogress';
import { AnimatePresence } from 'framer-motion';
import '../styles/index.css'

Router.events.on('routeChangeStart', () => NProgress.start());
Router.events.on('routeChangeComplete', () => NProgress.done());
Router.events.on('routeChangeError', () => NProgress.done());

export default function MyApp({ Component, pageProps }: AppProps) {
    return <AnimatePresence exitBeforeEnter>
        <Component {...pageProps} />
    </AnimatePresence>
}
