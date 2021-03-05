import React from 'react';
import Article from '../components/Article';
import Container from '../components/Container';
import Banner from '../components/Banner';
import Nav from '../components/Nav';
import Footer from '../components/Footer';
import { fetchAPI } from "@nx-secretarium-website/store/ui-data";

const Blog = ({ articles, homepage }) => {
  return (
    <div className="h-full min-h-full">
      <Banner />
      <Nav />
      <Container>
        <h1 className="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl sm:leading-10 text-center">{homepage.hero.title}</h1>
        <Article articles={articles} />
      </Container>
      <Footer />
    </div>
  );
};

export async function getStaticProps() {
  // Run API calls in parallel
  const [articles, homepage] = await Promise.all([
    fetchAPI('/articles?status=published'),
    fetchAPI('/homepage'),
  ]);

  return {
    props: { articles, homepage },
    revalidate: 1,
  };
}

export default Blog;
