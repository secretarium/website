import React from 'react';
import Button from './shared/Button';

const BlockChain = () => {
  return (
    <div className="bg-white relative py-24 ">
      <div className="px-4 sm:px-6 lg:px-8  mx-auto container max-w-3xl sm:text-center">
        <h3 className="text-3xl leading-8 font-extrabold tracking-tight text-red-600 sm:text-4xl sm:leading-10 lg:leading-none mb-2">
          Born from Blockchain
        </h3>
        <p className="my-4 text-xl leading-7 text-gray-700">
          Secretarium was created when the founders, Cedric and Bertrand,
          volunteered to build a blockchain lab for a major European bank. They
          soon realised that blockchains and DLTs weren’t sufficient for the
          level of data protection the bank needed. Dozens of prototypes later,
          a new era of confidential computing has begun…
        </p>
        <Button color="red-600" text="About Us" />
      </div>
    </div>
  );
};

export default BlockChain;
