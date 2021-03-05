import React from 'react';
import Image from './Image';

const Card = ({ article }) => {
  console.log(article);
  return (
    <figure className="mbg-gray-100 rounded-xl p-3">
      <h2 className="text-red-700 pb-3 uppercase text-center">{article.title}</h2>
      <Image image={article.image} />
      <div className="pt-6 text-center space-y-4">
        <blockquote>
          <p className="text-lg font-semibold">{article.description}</p>
        </blockquote>
        <figcaption className="font-medium">
          <div className="text-cyan-600 text-purple-600">{article.author.name}</div>
        </figcaption>
      </div>
    </figure>
  );
};

export default Card;
