import React from 'react';

const Carousel = () => {
  return (
    <div className="border-t border-gray-200 py-24 shadow-inner">
      <div className="mx-auto container">
      <div className="carousel relative bg-white">
        <div className="carousel-inner relative overflow-hidden ">
          {/** Slider 1 */}
          <input
            className="carousel-open"
            type="radio"
            id="carousel-1"
            name="carousel"
            aria-hidden="true"
            hidden
            checked
            readOnly
          />
          <div className="carousel-item absolute opacity-0">
            <div className="grid grid-cols-2 gap-4">
              <div className="pb-16 ...">
                <h3 className="text-3xl mx-auto leading-tight font-extrabold tracking-tight sm:text-4xl  lg:leading-none mt-2 text-red-600">
                  Tested by banks
                </h3>
                <p className="mt-4 text-xl leading-7 text-gray-700 pr-15 md:max-w-xs lg:max-w-md xl:max-w-2xl">
                  Enabling banks to collaborate securely on sensitive data for KYC and AML.
                </p>
              </div>
              <div className="...">
                <img src="/images/placeholder_600x360.png" />
              </div>
            </div>
          </div>
          <label
            htmlFor="carousel-2"
            className="prev control-1 w-10 h-10 ml-2 absolute cursor-pointer hidden text-3xl font-bold text-black hover:text-black rounded-full leading-tight text-center z-10 inset-y-0 left-0 my-auto border border-black"
          >
            ‹
          </label>
          <label
            htmlFor="carousel-2"
            className="next control-1 w-10 h-10 mr-2 absolute cursor-pointer hidden text-3xl font-bold text-red-600 hover:text-red-600 rounded-full leading-tight text-center z-10 inset-y-0 my-auto border border-red-600 left-20"
          >
            ›
          </label>
          {/** Slider 2 */}
          <input
            className="carousel-open"
            type="radio"
            id="carousel-2"
            name="carousel"
            aria-hidden="true"
            hidden
          />
          <div className="carousel-item absolute opacity-0">
          <div className="grid grid-cols-2 gap-4">
              <div className="pb-16 ...">
                <h3 className="text-3xl mx-auto leading-tight font-extrabold tracking-tight sm:text-4xl  lg:leading-none mt-2 text-red-600">
                  Tested by banks thought process
                </h3>
                <p className="mt-4 text-xl leading-7 text-gray-700 pr-15 md:max-w-xs lg:max-w-md xl:max-w-2xl">
                We believe everyone has the right to control their own data, people and businesses.
                </p>
              </div>
              <div className="...">
                <img src="/images/placeholder_600x360.png" />
              </div>
            </div>
          </div>
          <label
            htmlFor="carousel-1"
            className="prev control-2 w-10 h-10 ml-2 absolute cursor-pointer hidden text-3xl font-bold text-black hover:text-black rounded-full leading-tight text-center z-10 inset-y-0 left-0 my-auto border border-black"
          >
            ‹
          </label>
          <label
            htmlFor="carousel-1"
            className="next control-2 w-10 h-10 mr-2 absolute cursor-pointer hidden text-3xl font-bold text-red-600 hover:text-red-600 rounded-full leading-tight text-center z-10 inset-y-0 left-20 my-auto border border-red-600"
          >
            ›
          </label>
          {/** Bullet points */}
          <ol className="carousel-indicators">
            <li className="inline-block mr-3">
              <label
                htmlFor="carousel-1"
                className="cursor-pointer block text-5xl text-white hover:text-red-600"
              >
                <div className="carousel-bullet circle hover:text-red-600"></div>
              </label>
            </li>
            <li className="inline-block mr-3">
              <label
                htmlFor="carousel-2"
                className="cursor-pointer block text-5xl text-white hover:text-red-600"
              >
                <div className="carousel-bullet circle hover:text-red-600"></div>
              </label>
            </li>
          </ol>
        </div>
      </div>
    </div>
    </div>
  );
};

export default Carousel;
