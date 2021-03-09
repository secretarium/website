import * as React from 'react';
import Link from 'next/link';
import { ExternalLink } from './ExternalLink';
import Copyright from './Copyright';

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
        <Copyright />
      </div>
    </div>
  );
};

export default Footer;
