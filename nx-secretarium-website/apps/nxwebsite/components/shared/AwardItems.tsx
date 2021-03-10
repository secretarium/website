import React from 'react';

const AwardItems = () => {
  return (
    <div className="mt-11">
      <div className="lg:grid lg:grid-cols-4 lg:gap-8">
        <div>
          <p className="mt-2 lg:mt-4 text-base xl:text-lg lg:leading-normal leading-6 text-white text-center">
            No body sees the data (not even us)
          </p>
        </div>
        <div className="mt-10 lg:mt-0">
          <div>
            <p className="mt-2  lg:mt-4 text-base xl:text-lg lg:leading-normal leading-6 text-white text-center">
              No single point of failure
            </p>
          </div>
        </div>
        <div className="mt-10 lg:mt-0">
          <div>
            <p className="mt-2  lg:mt-4 text-base xl:text-lg lg:leading-normal leading-6 text-white text-center">
              Cryptographic proof of integrity
            </p>
          </div>
        </div>
        <div className="mt-10 lg:mt-0">
          <div>
            <p className="mt-2 lg:mt-4 text-base xl:text-lg lg:leading-normal leading-6 text-white text-center">
              Removes risk of data leakage
            </p>
          </div>
        </div>
      </div>
    </div>
  );
};

export default AwardItems;
