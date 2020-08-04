import Container from './container'
import { FontAwesomeIcon as Icon } from '@fortawesome/react-fontawesome'
import logo from '../public/assets/images/logoOutlineBlack.svg';
import { faTwitter, faLinkedinIn, faGithub, faDiscord } from '@fortawesome/free-brands-svg-icons'
import Link from 'next/link';

const Footer = () => {
    return (
        <footer className="bg-accent-1 border-t border-accent-2">
            <Container>
                <div className="py-28 flex flex-col lg:flex-row items-center">
                    <div className="leading-tight text-xl mb-10 lg:mb-0 lg:pr-4 lg:w-1/2">
                        <Link href="/">
                            <a title="Secretarium">
                                <img src={logo} alt="Secretarium" className="pb-8" />
                            </a>
                        </Link>
                        <div className="text-center text-lg lg:text-left mb-4 text-gray-700 pb-5">
                            Europe:<br />
                            1 Bank St<br />
                            London E14 4SG<br />
                            United Kingdom<br />
                            <a href="mailto:contact@secretarium.com">contact@secretarium.com</a>
                        </div>
                        <div className="text-center text-3xl lg:text-left">
                            <a className="pr-3 inline hover:text-gray-600 duration-200 transition-colors" href="https://github.com/secretarium" target="_blank"><Icon icon={faGithub} /></a>
                            <a className="pl-3 pr-3 inline hover:text-gray-600 duration-200 transition-colors" href="https://www.linkedin.com/company/secretarium/" target="_blank"><Icon icon={faLinkedinIn} /></a>
                            <a className="pl-3 pr-3 inline hover:text-gray-600 duration-200 transition-colors" href="https://discordapp.com/channels/670348155682029588/" target="_blank"><Icon icon={faDiscord} /></a>
                            <a className="pl-3 inline hover:text-gray-600 duration-200 transition-colors" href="https://twitter.com/secretarium" target="_blank"><Icon icon={faTwitter} /></a>
                        </div>
                    </div>
                    <div className="flex flex-col lg:flex-row justify-center items-center lg:pl-4 lg:w-1/2">
                        {/*  <a
                            href="https://nextjs.org/docs/basic-features/pages"
                            className="mx-3 bg-black hover:bg-white hover:text-black border border-black text-white font-bold py-3 px-12 lg:px-8 duration-200 transition-colors mb-6 lg:mb-0"
                        >
                            Read Documentation
            </a>
                        <a
                            href={`https://github.com/secretarium`}
                            target='_blank'
                            className="mx-3 font-bold hover:underline"
                        >
                            View on GitHub
            </a> */}
                    </div>
                </div>
                <div className="w-full text-center text-sm pb-20">
                    Secretarium © 2020 All Rights Reserved<br />
                    <Link href="/legal"><a className="text-red-500 hover:text-red-300">Legal information</a></Link> - <Link href="/legal/privacy-policy"><a className="text-red-500 hover:text-red-300">Privacy policy</a></Link>
                </div>
            </Container>
        </footer>
    )
}

export default Footer
