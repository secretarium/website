import React from 'react';
import { ExternalLink } from './ExternalLink';

const Copyright = () => {
  return (
    <div className="mt-8 border-t border-gray-200 pt-4">
      <div className="lg:grid lg:grid-cols-3 lg:gap-8">
        <div className="mt-10 lg:mt-0">
          <div className="text-white text-sm py-2">
            <div className="flex justify-between md:justify-start items-center flex-1">
              <ExternalLink
                href="#"
                className="rounded-md pr-2 inline-flex items-center leading-5 font-medium text-red-600 betterhover:hover:text-white focus:outline-none focus:text-red-900 focus:bg-red-600 transition duration-150 ease-in-out"
              >
                <span className="sr-only">Linkedin</span>
                <svg
                  className="fill-current w-5 h-5"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 192 192"
                >
                  <title>Linkedin</title>
                  <path d="M156,0h-120c-19.875,0 -36,16.125 -36,36v120c0,19.875 16.125,36 36,36h120c19.875,0 36,-16.125 36,-36v-120c0,-19.875 -16.125,-36 -36,-36zM59.36539,162.98077h-29.82693l-0.17307,-89.30769h29.82692zM43.70192,61.99038h-0.17308c-9.75,0 -16.03846,-6.72115 -16.03846,-15.08653c0,-8.56731 6.49039,-15.0577 16.41347,-15.0577c9.92308,0 16.00961,6.49038 16.21153,15.0577c0,8.36538 -6.31731,15.08653 -16.41346,15.08653zM162.77885,162.98077h-30.08654v-48.51923c0,-11.74039 -3.11538,-19.73077 -13.61538,-19.73077c-8.01923,0 -12.34615,5.39423 -14.42308,10.61538c-0.77885,1.875 -0.98077,4.44231 -0.98077,7.06731v50.56731h-30.23077l-0.17308,-89.30769h30.23077l0.17308,12.60577c3.86538,-5.97116 10.29808,-14.42308 25.70192,-14.42308c19.09616,0 33.37501,12.46154 33.37501,39.25961v51.86539z"></path>
                </svg>
              </ExternalLink>
              <ExternalLink
                href="#"
                className="rounded-md pr-2 inline-flex items-center leading-5 font-medium text-red-600 betterhover:hover:text-white focus:outline-none focus:text-red-900 focus:bg-red-600 transition duration-150 ease-in-out"
              >
                <span className="sr-only">Discord</span>
                <svg
                  className="fill-current w-5 h-5"
                  height="24"
                  viewBox="0 0 20 20"
                  width="24"
                  xmlns="http://www.w3.org/2000/svg"
                >
                  <title>Facebook</title>
                  <path
                    d="M8.258,4.458c0-0.144,0.02-0.455,0.06-0.931c0.043-0.477,0.223-0.976,0.546-1.5c0.32-0.522,0.839-0.991,1.561-1.406
                C11.144,0.208,12.183,0,13.539,0h3.82v4.163h-2.797c-0.277,0-0.535,0.104-0.768,0.309c-0.231,0.205-0.35,0.4-0.35,0.581v2.59h3.914
                c-0.041,0.507-0.086,1-0.138,1.476l-0.155,1.258c-0.062,0.425-0.125,0.819-0.187,1.182h-3.462v11.542H8.258V11.558H5.742V7.643
                h2.516V4.458z"
                  />
                </svg>
              </ExternalLink>
              <ExternalLink
                href="#"
                className="rounded-md pr-2 inline-flex items-center leading-5 font-medium text-red-600 betterhover:hover:text-white focus:outline-none focus:text-red-900 focus:bg-red-600 transition duration-150 ease-in-out"
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
          </div>
        </div>
        <div className="mt-10 lg:mt-0">
          <div className="text-white text-sm py-2 text-center">
            <span className="text-red-600">&copy; 2020 Secretarium.</span> All
            rights reserved.{' '}
          </div>
        </div>
        <div className="mt-10 lg:mt-0">
          <div className="flex relative w-46 float-right">
            <label className="flex-initial capilalize tracking-wide text-white text-xs font-bold mb-2 mr-2 my-3">
              Language
            </label>
            <div className="relative flex-initial">
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
  );
};

export default Copyright;
