import React from 'react';

const Summary = () => {
  return (
    <div className="text-lg">
      <div className="py-24">
        <div className="mx-auto container">
          <div className="lg:grid lg:grid-cols-1 lg:gap-8 md:max-w-xs lg:max-w-md xl:max-w-2xl">
            <div>
              <h3 className="text-3xl leading-8 font-extrabold tracking-tight sm:leading-10 lg:leading-none mt-2 text-red-600 sm:text-4xl">
                Proven confidential app hosting in the cloud
              </h3>
              <p className="mt-2 lg:mt-4 text-base xl:text-lg lg:leading-normal leading-6 text-gray-600">
                Secretarium secure cloud technology is powering a new wave of
                privacy-respecting products. Empowering individuals and
                businesses by guaranteeing data privacy.
              </p>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Summary;
