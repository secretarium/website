import React from 'react';
import Head from 'next/head';
import Link from 'next/link';
import Banner from '../components/Banner';
import Nav from '../components/Nav';
import Summary from '../components/Summary';
import Footer from '../components/Footer';
import Benefits from '../components/Benefits';
import BlockChain from '../components/BlockChain';

export function Index() {
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
        <Summary />
        <Benefits />
        <BlockChain />
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
