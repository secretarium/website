import Link from "next/link"
import navBarStyles from './navbar-styles.module.css'
import logo from '../public/assets/logo.svg';

const NavBar = () => {
    return (
        <div className={navBarStyles.navBarWrapper}>
            <div className={navBarStyles.navBar}>
                <Link href="/">
                    <a><img src={logo} /></a>
                </Link>
                <Link href="/blog">
                    <a className="hover:underline">Blog</a>
                </Link>
            </div>
        </div>
    )
}

export default NavBar
