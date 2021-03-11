import Link from 'next/link';
import { useRouter } from 'next/router';
import * as React from 'react';
import { ExternalLink } from './ExternalLink';
import { Logo } from './Logo';

const Nav = () => {
  const router = useRouter();
  return (
    <div className="bg-white border-b border-gray-200">
      <div className="container mx-auto">
        <div className="grid grid-cols-1 md:grid-cols-12 md:gap-8">
          <div className="md:col-span-3 flex items-center justify-between h-16">
            <div>
              <Link href="/" as="/">
                <a>
                  <span className="sr-only">Home</span>
                  <Logo />
                </a>
              </Link>
            </div>
          </div>
          <div className="md:col-span-9 items-center flex justify-between md:justify-end  space-x-6 h-16">
            <div className="flex justify-between md:justify-end items-center flex-1 md:space-x-2">
              <div>
                <Link href="/blog">
                  <a className="rounded-md py-2 px-3 inline-flex items-center leading-5 font-medium text-gray-900 betterhover:hover:bg-gray-50 betterhover:hover:text-gray-900 focus:outline-none focus:text-gray-900 focus:bg-gray-50 transition duration-150 ease-in-out">
                    Blog
                  </a>
                </Link>
              </div>
              <div>
                <Link href="/users">
                  <a className="rounded-md py-2 px-3 inline-flex items-center leading-5 font-medium text-gray-900 betterhover:hover:bg-gray-50 betterhover:hover:text-gray-900 focus:outline-none focus:text-gray-900 focus:bg-gray-50 transition duration-150 ease-in-out">
                    Users
                  </a>
                </Link>
              </div>
              <div>
                <ExternalLink
                  href="https://formium.io/contact/sales?utm_source=formik-site&utm_medium=navbar&utm_campaign=formik-website"
                  className="rounded-md py-2 px-3 inline-flex items-center leading-5 font-medium text-gray-900 betterhover:hover:bg-gray-50 betterhover:hover:text-gray-900 focus:outline-none focus:text-gray-900 focus:bg-gray-50 transition duration-150 ease-in-out"
                >
                  Enterprise
                </ExternalLink>
              </div>
              <div className="hidden lg:block">
                <ExternalLink
                  href="https://forms.formium.io/f/5f06126f5b703c00012005fa"
                  className="rounded-md py-2 px-3 inline-flex items-center leading-5 font-medium text-gray-900 betterhover:hover:bg-gray-50 betterhover:hover:text-gray-900 focus:outline-none focus:text-gray-900 focus:bg-gray-50 transition duration-150 ease-in-out"
                >
                  Feedback
                </ExternalLink>
              </div>
              <div>
                <ExternalLink
                  href='#'
                  className="rounded-md py-2 px-3 inline-flex items-center leading-5 font-medium text-red-600 betterhover:hover:bg-gray-50 betterhover:hover:text-red-700 focus:outline-none focus:text-red-900 focus:bg-red-600 transition duration-150 ease-in-out"
                >
                  <span className="sr-only">GitHub</span>
                  <svg
                    className="fill-current w-5 h-5"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 192 192"
                  >
                    <title>GitHub</title>
                    <path d="M156,0h-120c-19.875,0 -36,16.125 -36,36v120c0,19.875 16.125,36 36,36h120c19.875,0 36,-16.125 36,-36v-120c0,-19.875 -16.125,-36 -36,-36zM59.36539,162.98077h-29.82693l-0.17307,-89.30769h29.82692zM43.70192,61.99038h-0.17308c-9.75,0 -16.03846,-6.72115 -16.03846,-15.08653c0,-8.56731 6.49039,-15.0577 16.41347,-15.0577c9.92308,0 16.00961,6.49038 16.21153,15.0577c0,8.36538 -6.31731,15.08653 -16.41346,15.08653zM162.77885,162.98077h-30.08654v-48.51923c0,-11.74039 -3.11538,-19.73077 -13.61538,-19.73077c-8.01923,0 -12.34615,5.39423 -14.42308,10.61538c-0.77885,1.875 -0.98077,4.44231 -0.98077,7.06731v50.56731h-30.23077l-0.17308,-89.30769h30.23077l0.17308,12.60577c3.86538,-5.97116 10.29808,-14.42308 25.70192,-14.42308c19.09616,0 33.37501,12.46154 33.37501,39.25961v51.86539z"></path>
                  </svg>
                </ExternalLink>
              </div>

              <div className="hidden lg:block">
                <ExternalLink
                  href='#'
                  className="rounded-md py-2 px-3 inline-flex items-center leading-5 font-medium text-red-600 betterhover:hover:bg-gray-50 betterhover:hover:text-red-700 focus:outline-none focus:text-red-900 focus:bg-red-600 transition duration-150 ease-in-out"
                >
                  <span className="sr-only">Twitter</span>
                  <svg
                    className="fill-current w-5 h-5"
                    xmlns="http://www.w3.org/2000/svg"
                    viewBox="0 0 20 20"
                  >
                    <title>Twitter</title>
                    <path d="M6.29 18.25c7.55 0 11.67-6.25 11.67-11.67v-.53c.8-.59 1.49-1.3 2.04-2.13-.75.33-1.54.55-2.36.65a4.12 4.12 0 0 0 1.8-2.27c-.8.48-1.68.81-2.6 1a4.1 4.1 0 0 0-7 3.74 11.65 11.65 0 0 1-8.45-4.3 4.1 4.1 0 0 0 1.27 5.49C2.01 8.2 1.37 8.03.8 7.7v.05a4.1 4.1 0 0 0 3.3 4.03 4.1 4.1 0 0 1-1.86.07 4.1 4.1 0 0 0 3.83 2.85A8.23 8.23 0 0 1 0 16.4a11.62 11.62 0 0 0 6.29 1.84"></path>
                  </svg>
                </ExternalLink>
              </div>
              <div>
                <ExternalLink
                  href='#'
                  className="rounded-md py-2 px-3 inline-flex items-center leading-5 font-medium text-red-600 betterhover:hover:bg-gray-50 betterhover:hover:text-red-700 focus:outline-none focus:text-red-900 focus:bg-red-600 transition duration-150 ease-in-out"
                >
                  <span className="sr-only">Discord</span>
                  <svg
                    className="fill-current w-5 h-5" height="24" viewBox="0 0 20 20" width="24" xmlns="http://www.w3.org/2000/svg"
                  >
                    <title>Facebook</title>
                    <path d="M8.258,4.458c0-0.144,0.02-0.455,0.06-0.931c0.043-0.477,0.223-0.976,0.546-1.5c0.32-0.522,0.839-0.991,1.561-1.406
                C11.144,0.208,12.183,0,13.539,0h3.82v4.163h-2.797c-0.277,0-0.535,0.104-0.768,0.309c-0.231,0.205-0.35,0.4-0.35,0.581v2.59h3.914
                c-0.041,0.507-0.086,1-0.138,1.476l-0.155,1.258c-0.062,0.425-0.125,0.819-0.187,1.182h-3.462v11.542H8.258V11.558H5.742V7.643
                h2.516V4.458z" />
                  </svg>
                </ExternalLink>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Nav;
