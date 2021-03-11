import React from 'react';
import AwardItems from './shared/AwardItems';

const Summary = () => {
  return (
    <div className="relative">
      <div className="text-lg">
        <div className="py-24">
          <div className="mx-auto container">
            <h3 className="text-3xl leading-8 font-extrabold tracking-tight sm:leading-10 lg:leading-none mt-2 text-red-600 sm:text-4xl md:max-w-xs lg:max-w-md xl:max-w-2xl">
                Proven confidential app hosting in the cloud
              </h3>
              <p className="my-4 text-base xl:text-lg lg:leading-normal leading-6 text-gray-600 md:max-w-xs lg:max-w-md xl:max-w-xl">
                Secretarium secure cloud technology is powering a new wave of
                privacy-respecting products. Empowering individuals and
                businesses by guaranteeing data privacy.
              </p>
          </div>
        </div>
      </div>
      <div className="static">
        <div className="relative py-24 border-t section-data-processing">
          <div className="mx-auto container">
            <h3 className="text-3xl leading-8 font-extrabold tracking-tight sm:leading-10 lg:leading-none mt-2 text-white sm:text-4xl md:max-w-xs lg:max-w-md xl:max-w-2xl">
              Truly secure data processing
            </h3>
            <p className="my-4 text-xl leading-7 text-white md:max-w-xs lg:max-w-md xl:max-w-2xl">
              Secretarium secure cloud technology uses a combination of secure
              hardware and cryptography to ensure total data privacy. It’s the
              only technology that can guarantee data encryption during
              processing.
            </p>
            <AwardItems />
          </div>
        </div>
        <div className="absolute h-64 top-28 left-2/4 right-3.5">
          <img src="/images/placeholder_600x360.png" className="rounded-lg" />
        </div>
      </div>
    </div>
  );
};

export default Summary;
