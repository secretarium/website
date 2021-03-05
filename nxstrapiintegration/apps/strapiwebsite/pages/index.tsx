import React from 'react';
import Articles from "../components/Article";
import styles from './index.module.css';
import { fetchAPI } from "../lib/api";

export function Index({articles, homepage}) {
  /*
   * Replace the elements below with your own.
   *
   * Note: The corresponding styles are in the ./index.css file.
   */

  return (
    <div className="uk-section">
      <div className="uk-container uk-container-large">
        <h1>{homepage.hero.title}</h1>
        <p>Thank you for using and showing some ♥ for Nx.</p>
        <Articles articles={articles} />
      </div>
    </div>
  );
}

export async function getStaticProps() {
  // Run API calls in parallel
  const [articles, homepage] = await Promise.all([
    fetchAPI("/articles?status=published"),
    fetchAPI("/homepage"),
  ]);

  return {
    props: { articles, homepage },
    revalidate: 1,
  };
}

export default Index;
