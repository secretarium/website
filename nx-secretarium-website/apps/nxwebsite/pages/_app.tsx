import React from 'react';
import { AppProps } from 'next/app';
import App from "next/app";
import Head from 'next/head';
import './styles.css';

import { createContext } from "react";
import { getStrapiMedia } from "@nx-secretarium-website/store/ui-media";
import { fetchAPI } from "@nx-secretarium-website/store/ui-data";

// Store Strapi Global object in context
export const GlobalContext = createContext({});

function CustomApp({ Component, pageProps }: AppProps) {
  return (
    <>
      <Head>
        <title>Welcome to strapiwebsite!</title>
        <link rel="shortcut icon" href={getStrapiMedia(pageProps.global.favicon)}/>
        <link rel="preconnect" href="https://fonts.gstatic.com"/>
        <link href="https://fonts.googleapis.com/css2?family=Open+Sans&display=swap" rel="stylesheet"/>
      </Head>
      <div className="app">
        <main>
        <GlobalContext.Provider value={pageProps.global}>
          <Component {...pageProps} />
        </GlobalContext.Provider>
        </main>
      </div>
    </>
  );
};

CustomApp.getInitialProps = async (ctx) => {
  // Calls page's `getInitialProps` and fills `appProps.pageProps`
  const appProps = await App.getInitialProps(ctx);
  // Fetch global site settings from Strapi
  const global = await fetchAPI("/global");
  // Pass the data to our page via props
  return { ...appProps, pageProps: { global } };
};

export default CustomApp;
