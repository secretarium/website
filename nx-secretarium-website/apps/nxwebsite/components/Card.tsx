import React from 'react';
import Link from 'next/link';
import Image from './Image';

const Card = ({ article }) => {
  console.log('getting data:',article);
  return (
    <figure className="mbg-gray-100 rounded-xl p-3">
      <Image image={article.image} />
      <div className="pt-6 text-center space-y-4">
        <blockquote>
          <p className="text-lg font-semibold">{article.description}</p>
        </blockquote>
        <figcaption className="font-medium">
          <div className="text-cyan-600">{article.author.name}</div>
        </figcaption>
      </div>
    </figure>
  );
};

export default Card;
