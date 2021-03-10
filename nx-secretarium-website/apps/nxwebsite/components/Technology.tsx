import React from 'react';
import Button from './shared/Button';

const Technology = () => {
  return (
    <section className="bg-hero-pattern body-font bg-no-repeat bg-cover">
      <div className="py-24 px-4 sm:px-6 lg:px-8  mx-auto container">
        <div className=" sm:text-center pt-20">
          <h3 className="text-3xl mx-auto leading-tight font-extrabold tracking-tight text-white sm:text-4xl lg:leading-none mb-6">
            Technology
          </h3>
         <Button color="red-600" text="Learn more"/>
        </div>
      </div>
    </section>
  );
};

export default Technology;
