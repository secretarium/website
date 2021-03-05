import React from 'react';
import Article from '../components/Article';
import Container from '../components/Container';
import Banner from '../components/Banner';
import Nav from '../components/Nav';
import { fetchAPI } from '../lib/api';

const Blog = ({ articles, homepage }) => {
  return (
    <div className="h-full min-h-full">
      <Banner />
      <Nav />
      <Container>
        <h1 className="text-6xl capitalize mb-5 text-center">{homepage.hero.title}</h1>
        <Article articles={articles} />
      </Container>
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
