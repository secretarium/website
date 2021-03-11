import * as React from 'react';
import Link from 'next/link';
import { ExternalLink } from './ExternalLink';
import Copyright from './Copyright';

const Footer = () => {
  return (
    <div className="bg-black">
      <div className="container mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8">
        <div className="lg:grid lg:grid-cols-3 lg:gap-8 pb-4">
          <div className="lg:grid lg:grid-cols-3 gap-8 lg:col-span-2">
            <div className="mt-12 lg:mt-0">
              <img src="/images/secretarium_icon.png" className="mb-3" />
              <p className="text-white text-3xl">
                The secure <br /> cloud.
              </p>
            </div>
            <div className="mt-12 lg:mt-20">
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
            <div className="mt-12 lg:mt-20">
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
              className="mt-4 sm:flex sm:max-w-md relative flex-initial"
            >
              <input type="hidden" name="_email" value="" />
              <input
                aria-label="Email address"
                type="email"
                name="email"
                required={true}
                className="appearance-none w-full px-4 py-3 border text-base leading-6 text-gray-900 bg-black placeholder-white focus:outline-none focus:shadow-outline-blue focus:border-blue-300 transition duration-150 ease-in-out border-white hover:border-red-600"
                placeholder="Email Address"
              />
              <div className="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-red-600">
                <svg
                  className="fill-current h-4 w-4"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 492 492"
                >
                  <path
                    d="M484.14,226.886L306.46,49.202c-5.072-5.072-11.832-7.856-19.04-7.856c-7.216,0-13.972,2.788-19.044,7.856l-16.132,16.136
			                c-5.068,5.064-7.86,11.828-7.86,19.04c0,7.208,2.792,14.2,7.86,19.264L355.9,207.526H26.58C11.732,207.526,0,219.15,0,234.002
			                v22.812c0,14.852,11.732,27.648,26.58,27.648h330.496L252.248,388.926c-5.068,5.072-7.86,11.652-7.86,18.864
		            	    c0,7.204,2.792,13.88,7.86,18.948l16.132,16.084c5.072,5.072,11.828,7.836,19.044,7.836c7.208,0,13.968-2.8,19.04-7.872
			                l177.68-177.68c5.084-5.088,7.88-11.88,7.86-19.1C492.02,238.762,489.228,231.966,484.14,226.886z"
                  />
                </svg>
              </div>
            </form>
          </div>
        </div>
        <Copyright />
      </div>
    </div>
  );
};

export default Footer;
