import React from 'react';
import Head from 'next/head';
import Link from 'next/link';
import Banner from '../components/Banner';
import Nav from '../components/Nav';
import Footer from '../components/Footer';
import Sticky from '../components/Sticky';

export function Index() {
  /*
   * Replace the elements below with your own.
   *
   * Note: The corresponding styles are in the ./index.css file.
   */
  return (
    <>
      <Head>
        <title>Formik: Build forms in React, without the tears</title>
      </Head>
      <div className="bg-gray-50 h-full min-h-full">
        <Banner />
        <Nav />
        <div className="relative bg-white overflow-hidden">
          <div className="hidden lg:block lg:absolute lg:inset-0">
            <svg
              className="absolute top-0 left-1/2 transform translate-x-64 -translate-y-8"
              width="640"
              height="784"
              fill="none"
              viewBox="0 0 640 784"
            >
              <defs>
                <pattern
                  id="9ebea6f4-a1f5-4d96-8c4e-4c2abf658047"
                  x="118"
                  y="0"
                  width="20"
                  height="20"
                  patternUnits="userSpaceOnUse"
                >
                  <rect
                    x="0"
                    y="0"
                    width="4"
                    height="4"
                    className="text-gray-200"
                    fill="currentColor"
                  />
                </pattern>
              </defs>
              <rect
                x="118"
                width="404"
                height="784"
                fill="url(#9ebea6f4-a1f5-4d96-8c4e-4c2abf658047)"
              />
            </svg>
          </div>

          <div className="py-24 mx-auto container px-4 sm:mt-12  relative">
            <div className="hidden lg:block absolute lg:w-3/5 right-0 lg:-rotate-30 lg:translate-x-1/3 lg:-translate-y-16 md:w-1/2 sm:w-2/3 top-0  transform  -translate-y-12">
              <img src="/images/hero6.png" width={1042} height={990} />
            </div>
            <div className="grid grid-cols-12 gap-8">
              <div className="col-span-12 lg:col-span-6 ">
                <div className="text-center lg:text-left md:max-w-2xl md:mx-auto ">
                  <h1 className="text-4xl tracking-tight leading-10 font-extrabold text-gray-900 sm:leading-none sm:text-6xl lg:text-5xl xl:text-6xl">
                    Build forms in React,
                    <br className="hidden md:inline xl:hidden" />{' '}
                    <span>without the tears</span>
                  </h1>
                  <p className="mt-3 text-base text-gray-700 sm:mt-5 sm:text-xl lg:text-lg xl:text-xl">
                    Formik is the world's most popular open source form library
                    for React and React Native.
                  </p>

                  <div className="mt-5  mx-auto sm:flex sm:justify-center lg:justify-start lg:mx-0 md:mt-8">
                    <div className="rounded-md shadow">
                      <Link href="/docs/overview">
                        <a className="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:border-blue-700 focus:shadow-outline-blue transition duration-150 ease-in-out md:py-4 md:text-lg md:px-10">
                          Get Started
                        </a>
                      </Link>
                    </div>
                    <div className="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                      <a
                        href="#"
                        target="_blank"
                        rel="noopener noreferrer"
                        className="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-blue-600 bg-white hover:text-blue-500 focus:outline-none focus:border-blue-300 focus:shadow-outline-blue transition duration-150 ease-in-out md:py-4 md:text-lg md:px-10"
                      >
                        GitHub
                      </a>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div className="text-lg border-t border-gray-100 bg-gray-50 ">
          <div className="py-24">
            <div className="mx-auto container">
              <div className="lg:grid lg:grid-cols-3 lg:gap-8">
                <div>
                  <div>
                    <h3 className="text-xl leading-6 xl:text-2xl font-bold text-gray-900">
                      Declarative
                    </h3>
                    <p className="mt-2 lg:mt-4 text-base xl:text-lg lg:leading-normal leading-6 text-gray-600">
                      Formik takes care of the repetitive and annoying
                      stuff—keeping track of values/errors/visited fields,
                      orchestrating validation, and handling submission—so you
                      don't have to. This means you spend less time wiring up
                      state and change handlers and more time focusing on your
                      business logic.
                    </p>
                  </div>
                </div>
                <div className="mt-10 lg:mt-0">
                  <div>
                    <h3 className="text-xl leading-6 xl:text-2xl font-bold text-gray-900">
                      Intuitive
                    </h3>
                    <p className="mt-2  lg:mt-4 text-base xl:text-lg lg:leading-normal leading-6 text-gray-600">
                      No fancy subscriptions or observables under the hood, just
                      plain React state and props. By staying within the core
                      React framework and away from magic, Formik makes
                      debugging, testing, and reasoning about your forms a
                      breeze. If you know React, and you know a bit about forms,
                      you know Formik!
                    </p>
                  </div>
                </div>
                <div className="mt-10 lg:mt-0">
                  <div>
                    <h3 className="text-xl leading-6 xl:text-2xl font-bold text-gray-900">
                      Adoptable
                    </h3>
                    <p className="mt-2  lg:mt-4 text-base xl:text-lg lg:leading-normal leading-6 text-gray-600">
                      Since form state is inherently local and ephemeral, Formik
                      does not use external state management libraries like
                      Redux or MobX. This also makes Formik easy to adopt
                      incrementally and keeps bundle size to a minimum.
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <div className="py-6">
            <div className="uppercase tracking-wider text-sm font-semibold text-center  text-gray-400">
              Trusted in Production by
            </div>
          </div>
        </div>

        <div className="relative py-24 border-t section-data-processing">
          <div className="mx-auto container">
            <h3 className="text-3xl leading-8 font-extrabold tracking-tight sm:leading-10 lg:leading-none mt-2 text-white sm:text-4xl">
              Truly secure data processing
            </h3>
            <p className="my-4 text-xl leading-7 text-white max-w-2xl">
              Secretarium secure cloud technology uses a combination of secure
              hardware and cryptography to ensure total data privacy. It’s the
              only technology that can guarantee data encryption during
              processing.
            </p>
            <div className="mt-11">
              <div className="lg:grid lg:grid-cols-4 lg:gap-8">
                <div>
                  <p className="mt-2 lg:mt-4 text-base xl:text-lg lg:leading-normal leading-6 text-white text-center">
                    No body sees the data (not even us)
                  </p>
                </div>
                <div className="mt-10 lg:mt-0">
                  <div>
                    <p className="mt-2  lg:mt-4 text-base xl:text-lg lg:leading-normal leading-6 text-white text-center">
                      No single point of failure
                    </p>
                  </div>
                </div>
                <div className="mt-10 lg:mt-0">
                  <div>
                    <p className="mt-2  lg:mt-4 text-base xl:text-lg lg:leading-normal leading-6 text-white text-center">
                      Cryptographic proof of integrity
                    </p>
                  </div>
                </div>
                <div className="mt-10 lg:mt-0">
                  <div>
                    <p className="mt-2  lg:mt-4 text-base xl:text-lg lg:leading-normal leading-6 text-white text-center">
                      Removes risk of data leakage
                    </p>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <section className="bg-white body-font">
          <div className="py-24 mx-auto container">
            <div className="grid grid-cols-3 gap-4">
              <div className="pb-16 col-span-2 ...">
                <h3 className="text-3xl mx-auto leading-tight font-extrabold tracking-tight sm:text-4xl  lg:leading-none mt-2 text-red-600">
                  Say hello to total data control
                </h3>
                <p className="mt-4 text-xl max-w-3xl mx-auto leading-7 text-gray-700 pr-15">
                  We believe everyone has the right to control their own data,
                  people and businesses. The ability to protect sensitive
                  information during processing opens the door to endless
                  commercial data opportunities, without compromising individual
                  privacy.
                </p>
              </div>
              <div className="...">
              <div className="grid grid-flow-row grid-cols-1 sm:grid-cols-2 gap-4 text-gray-700 max-w-screen-lg mx-auto text-lg">
                <a className="mb-1">
                  <span className="bg-blue-100 text-blue-500 w-4 h-4 mr-2 rounded-full inline-flex items-center justify-center">
                    <Check />
                  </span>
                  Lets users keep <strong>control</strong> of their data
                </a>
                <a className="mb-1">
                  <span className="bg-blue-100 text-blue-500 w-4 h-4 mr-2 rounded-full inline-flex items-center justify-center">
                    <Check />
                  </span>
                  <strong>Secures</strong> commercial data
                </a>
                <a className="mb-1">
                  <span className="bg-blue-100 text-blue-500 w-4 h-4 mr-2 rounded-full inline-flex items-center justify-center">
                    <Check />
                  </span>
                  Powers secure <strong>monetisation</strong>
                </a>
                <a className="mb-1">
                  <span className="bg-blue-100 text-blue-500 w-4 h-4 mr-2 rounded-full inline-flex items-center justify-center">
                    <Check />
                  </span>
                  Enables secure <strong>collaboration</strong>
                </a>
                <a className="mb-1">
                  <span className="bg-blue-100 text-blue-500 w-4 h-4 mr-2 rounded-full inline-flex items-center justify-center">
                    <Check />
                  </span>
                  <strong>Protects</strong> anonymity
                </a>
              </div>
              </div>
            </div>
          </div>
        </section>
        <div className="bg-gray-50 border-b border-gray-100">
          <div className="container mx-auto py-12 px-4 sm:px-6 lg:py-16 lg:px-8 lg:flex lg:items-center lg:justify-between">
            <h2 className="text-3xl leading-9 font-extrabold tracking-tight text-gray-900 sm:text-4xl sm:leading-10">
              Ready to dive in?
            </h2>
            <div className="mt-8 flex lg:flex-shrink-0 lg:mt-0">
              <div className="inline-flex rounded-md shadow">
                <Link href="/docs/overview">
                  <a className="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-white bg-blue-600 hover:bg-blue-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out">
                    Get Started
                  </a>
                </Link>
              </div>
              <div className="ml-3 inline-flex rounded-md shadow">
                <a
                  href="#"
                  className="inline-flex items-center justify-center px-5 py-3 border border-transparent text-base leading-6 font-medium rounded-md text-blue-600 bg-white hover:text-blue-500 focus:outline-none focus:shadow-outline transition duration-150 ease-in-out"
                >
                  GitHub
                </a>
              </div>
            </div>
          </div>
        </div>
        <Footer />
        <style jsx global>{`
          .gradient {
            -webkit-mask-image: linear-gradient(
              180deg,
              transparent 0,
              #000 30px,
              #000 calc(100% - 200px),
              transparent calc(100% - 100px)
            );
          }
        `}</style>
      </div>
    </>
  );
}

export default Index;

const Check = React.memo(() => (
  <svg
    fill="none"
    stroke="currentColor"
    strokeLinecap="round"
    strokeLinejoin="round"
    strokeWidth="3"
    className="w-3 h-3"
    viewBox="0 0 24 24"
    aria-hidden="true"
  >
    <path d="M20 6L9 17l-5-5"></path>
  </svg>
));
