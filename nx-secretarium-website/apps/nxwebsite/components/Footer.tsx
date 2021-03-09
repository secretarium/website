import * as React from 'react';
import Link from 'next/link';
import { ExternalLink } from './ExternalLink';

const Footer = () => {
  return (
    <div className="bg-black">
      <div className="container mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
        <div className="lg:grid lg:grid-cols-3 lg:gap-8">
          <div className="lg:grid lg:grid-cols-3 gap-8 lg:col-span-2">
            <div className="mt-12 lg:mt-0">
              <h4 className="text-sm leading-5 font-semibold tracking-wider text-gray-400 uppercase">
                Resources
              </h4>
              <ul className="mt-4">
                <li>
                  <Link href="/docs/overview">
                    <a className="text-base leading-6 text-gray-500 hover:text-gray-900">
                      Docs
                    </a>
                  </Link>
                </li>
                <li className="mt-4">
                  <Link href="/docs/tutorial">
                    <a className="text-base leading-6 text-gray-500 hover:text-gray-900">
                      Learn
                    </a>
                  </Link>
                </li>
                <li className="mt-4">
                  <Link href="/docs/guides/validation">
                    <a className="text-base leading-6 text-gray-500 hover:text-gray-900">
                      Guides
                    </a>
                  </Link>
                </li>
                <li className="mt-4">
                  <Link href="/docs/api/formik">
                    <a className="text-base leading-6 text-gray-500 hover:text-gray-900">
                      API Reference
                    </a>
                  </Link>
                </li>

                <li className="mt-4">
                  <Link href="/blog">
                    <a className="text-base leading-6 text-gray-500 hover:text-gray-900">
                      Blog
                    </a>
                  </Link>
                </li>
              </ul>
            </div>
            <div className="mt-12 lg:mt-0">
              <ul className="mt-4">
                <li>
                  <Link href="/users">
                    <a className="text-base leading-6 text-white hover:text-white">
                      Home
                    </a>
                  </Link>
                </li>
                <li className="mt-4">
                  <Link href="https://opencollective.com/formik">
                    <a className="text-base leading-6 text-white hover:text-white">
                      About Us
                    </a>
                  </Link>
                </li>
                <li className="mt-4">
                  <Link href="#">
                    <a className="text-base leading-6 text-white hover:text-white">
                      Tech
                    </a>
                  </Link>
                </li>
              </ul>
            </div>
            <div className="mt-12 lg:mt-0">
              <ul className="mt-4">
                <li className="mt-4">
                  <ExternalLink
                    href="https://formium.io?utm_source=formik-site&utm_medium=footer-link&utm_campaign=formik-website"
                    className="text-base leading-6 text-white hover:text-white"
                  >
                    Contact Us
                  </ExternalLink>
                </li>
                <li className="mt-4">
                  <ExternalLink
                    href="https://github.com/formium"
                    className="text-base leading-6 text-white hover:text-white"
                  >
                    Terms &amp; Conditions
                  </ExternalLink>
                </li>
                <li className="mt-4">
                  <ExternalLink
                    href="https://twitter.com/formiumhq"
                    className="text-base leading-6 text-white hover:text-white"
                  >
                    Privacy Policy
                  </ExternalLink>
                </li>
              </ul>
            </div>
          </div>
          <div className="mt-8 lg:mt-0">
            <p className="mt-4 text-white text-base leading-6">
              <strong>Say hello! </strong>
              <span className="font-light">
                Sign up to our newsletter for company updates, technical tips,
                benefits and offers.
              </span>
            </p>
            <form
              action="https://api.formik.com/submit/palmerhq/formik-newsletter"
              method="post"
              className="mt-4 sm:flex sm:max-w-md"
            >
              <input type="hidden" name="_email" value="" />
              <input
                aria-label="Email address"
                type="email"
                name="email"
                required={true}
                className="appearance-none w-full px-4 py-3 border text-base leading-6 text-gray-900 bg-black placeholder-white focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out border-white"
                placeholder="Email Address"
              />
            </form>
          </div>
        </div>
        <div className="mt-8 border-t border-gray-200 pt-8">
          <div className="lg:grid lg:grid-cols-3 lg:gap-8">
            <div className="mt-10 lg:mt-0">
              <div className="text-white text-sm py-3">
                <span className="text-red-600">&copy; 2020 Secretarium.</span>{' '}
                All rights reserved.{' '}
              </div>
            </div>
            <div className="mt-10 lg:mt-0">
              <div className="text-white text-sm py-3 text-center">
                <span className="text-red-600">&copy; 2020 Secretarium.</span>{' '}
                All rights reserved.{' '}
              </div>
            </div>
            <div className="mt-10 lg:mt-0">
              <div className="flex relative w-48 float-right">
                <label className="flex-initial uppercase tracking-wide text-white text-xs font-bold mb-2 mr-2 my-3">
                  Language
                </label>
                <div className="relative flex-initial w-24">
                  <select className="block appearance-none w-full bg-black border border-white hover:border-gray-500 px-4 py-2 pr-8 shadow leading-tight focus:outline-none focus:shadow-outline text-white">
                    <option>English</option>
                    <option>French</option>
                    <option>Option 3</option>
                  </select>
                  <div className="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-red-600">
                    <svg
                      className="fill-current h-4 w-4"
                      xmlns="http://www.w3.org/2000/svg"
                      viewBox="0 0 20 20"
                    >
                      <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                    </svg>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Footer;
