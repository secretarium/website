import Link from "next/link"
import navBarStyles from './nav-bar-styles.module.css'
import logo from '../public/assets/logo.svg';
import { useState } from "react";

const NavBar = () => {

    const [isExpanded, setIsExpanded] = useState(false);

    return (
        <div className={`border-b border-accent-2 ${navBarStyles.navBarWrapper}`}>
            <div className={`container mx-auto px-5 md:text-center ${navBarStyles.navBar}`}>
                <nav className="flex items-center justify-between flex-wrap">
                    <Link href="/">
                        <a className="flex items-center flex-shrink-0 text-white mr-6">
                            <img src={logo} alt="Secretarium" className="inline-block" />
                        </a>
                    </Link>
                    <div className="block md:hidden">
                        <button className="flex items-center px-3 py-2 border rounded">
                            <svg className="fill-current h-3 w-3" viewBox="0 0 20 20" xmlns="http://www.w3.org/2000/svg"><title>Menu</title><path d="M0 3h20v2H0V3zm0 6h20v2H0V9zm0 6h20v2H0v-2z" /></svg>
                        </button>
                    </div>
                    <div className={`w-full flex-grow md:flex md:items-right md:w-auto bg-white ${isExpanded ? 'block' : 'hidden'}`}>
                        <div className="text-xl md:flex-grow text-right">
                            <Link href="/blog">
                                <a className="block mt-4 md:inline-block md:mt-0 mr-12">Blog</a>
                            </Link>
                            <a href="#documentation" className="block mt-4 md:inline-block md:mt-0">Docs</a>
                        </div>
                    </div>
                </nav>
            </div>
        </div>
    )
}

export default NavBar
