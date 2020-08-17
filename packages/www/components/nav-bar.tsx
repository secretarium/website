import Link from "next/link"
import navBarStyles from './nav-bar-styles.module.css'
import logo from '../public/assets/images/logo.svg';
import { useState } from "react";

type NavBarProps = {
    fixedToggle?: boolean;
}

const NavBar: React.FC<NavBarProps> = ({ fixedToggle }) => {

    const [isExpanded, setIsExpanded] = useState(false);

    const menuItems = (
        <div className="text-xl md:flex-grow text-left md:text-right">
            <Link href="/">
                <a className="block mt-4 md:inline md:mt-0 mr-12 duration-200 transition-colors hover:text-red-500">product</a>
            </Link>
            <Link href="/blog">
                <a className="block mt-4 md:inline md:mt-0 mr-12 duration-200 transition-colors hover:text-red-500">blog</a>
            </Link>
            <a href="#contact" className="block mt-4 md:inline md:mt-0 duration-200 transition-colors text-white bg-red-500 hover:bg-red-400 rounded-lg px-3 py-2" onClick={() => setIsExpanded(false)}>contact</a>
        </div>
    )

    return (
        <div className={`border-b border-accent-2 ${navBarStyles.navBarWrapper} ${fixedToggle ? '' : navBarStyles.fixedToggle}`}>
            <div className={`container mx-auto px-5 md:text-center ${navBarStyles.navBar}`}>
                <nav className="flex items-center justify-between flex-wrap">
                    <Link href="/">
                        <a className={`flex items-center flex-shrink-0 text-white mr-6 ${navBarStyles.logoWrapper}`}>
                            <img src={logo} alt="Secretarium" className=" inline-block" />
                        </a>
                    </Link>
                    <div className="block md:hidden">
                        <div className={navBarStyles.plate} onClick={() => setIsExpanded(!isExpanded)}>
                            <div className={`${navBarStyles.burger} ${isExpanded ? navBarStyles.open : ''}`}></div>
                        </div>
                    </div>
                    <div className={`w-full flex-grow md:flex md:items-right md:w-auto hidden`}>
                        {menuItems}
                    </div>
                </nav>
            </div>
            {isExpanded
                ? (
                    <div className={`bg-white p-5 pb-6 ${navBarStyles.menuPanelShadow}`}>
                        {menuItems}
                    </div>
                )
                : null
            }
        </div>
    )
}

export default NavBar
