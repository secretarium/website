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
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Nav;
