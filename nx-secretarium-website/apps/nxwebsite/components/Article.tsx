import React from 'react';
import Card from './Card';

const Article = ({ articles }) => {
  return (
    <div className="grid grid-cols-2 gap-4">
      {articles.map((article: any, i: any) => {
        return <Card article={article} key={article.id} />
      })}
    </div>
  );
};

export default Article;
