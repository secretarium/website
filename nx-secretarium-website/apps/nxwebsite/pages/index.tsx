import React from 'react';
import Head from 'next/head';
import Link from 'next/link';
import Banner from '../components/Banner';
import Nav from '../components/Nav';
import Summary from '../components/Summary';
import Footer from '../components/Footer';
import Benefits from '../components/Benefits';
import BlockChain from '../components/BlockChain';
import Technology from '../components/Technology';
import Button from '../components/shared/Button';

export function Index() {
  return (
    <>
      <Head>
        <title>Secretarium website, cryptography, without the tears</title>
      </Head>
      <div className="bg-gray-50 h-full min-h-full">
        <Banner />
        <Nav />
        <div className="relative bg-white overflow-hidden">
          <div className="py-24 mx-auto container px-4 sm:mt-12  relative">
            <div className="hidden lg:block absolute lg:w-3/5 right-0 lg:-rotate-30 lg:translate-x-1/3 lg:-translate-y-16 md:w-1/2 sm:w-2/3 top-0  transform  -translate-y-12">
              <img src="/images/hero6.png" width={1042} height={990} />
            </div>
            <div className="grid grid-cols-12 gap-8">
              <div className="col-span-12 lg:col-span-6 ">
                <div className="text-center lg:text-left md:max-w-2xl md:mx-auto ">
                  <h1 className="text-3xl tracking-tight leading-10 font-extrabold text-gray-900 sm:leading-none sm:text-6xl lg:text-5xl xl:text-5xl">
                    At the heart
                    <br/>
                    <span>of confidential</span>
                    <br/>
                    <span>computing</span>
                  </h1>
                  <p className="mt-3 text-base text-gray-600 sm:mt-5 sm:text-xl lg:text-lg xl:text-xl md:max-w-xs lg:max-w-md xl:max-w-xl mb-4">
                    Award-winning technology, changing the face of data privacy one app at a time. 
                  </p>
                  <Button color="red-600" text="Read more"/>
                </div>
              </div>
            </div>
          </div>
        </div>
        <Summary />
        <Benefits />
        <Technology />
        <BlockChain />
        <Footer />
      </div>
    </>
  );
}

export default Index;
