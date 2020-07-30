import { motion } from 'framer-motion';
import Alert from './alert'
import Footer from './footer'
import Meta from './meta'
import Sticky from 'react-sticky-el';
import NavBar from './nav-bar';
import layoutStyles from './layout-styles.module.css'

type Props = {
    preview?: boolean
    children: React.ReactNode
}

const Layout = ({ preview, children }: Props) => {
    return (
        <>
            <Meta />
            <Sticky
                className={layoutStyles.stickyPanel}
                stickyClassName={layoutStyles.stuck}>
                <NavBar />
            </Sticky>
            <motion.div
                initial={{ opacity: 0 }}
                animate={{ opacity: 1 }}
                exit={{ opacity: 0 }}
                className="min-h-screen">
                <Alert preview={preview} />
                <main>{children}</main>
            </motion.div>
            <Footer />
        </>
    )
}

export default Layout
