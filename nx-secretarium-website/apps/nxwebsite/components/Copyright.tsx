import React from 'react';

const Copyright = () => {
  return (
    <div className="mt-8 border-t border-gray-200 pt-8">
      <div className="lg:grid lg:grid-cols-3 lg:gap-8">
        <div className="mt-10 lg:mt-0">
          <div className="text-white text-sm py-3">
            <span className="text-red-600">&copy; 2020 Secretarium.</span> All
            rights reserved.{' '}
          </div>
        </div>
        <div className="mt-10 lg:mt-0">
          <div className="text-white text-sm py-3 text-center">
            <span className="text-red-600">&copy; 2020 Secretarium.</span> All
            rights reserved.{' '}
          </div>
        </div>
        <div className="mt-10 lg:mt-0">
          <div className="flex relative w-46 float-right">
            <label className="flex-initial capilalize tracking-wide text-white text-xs font-bold mb-2 mr-2 my-3">
              Language
            </label>
            <div className="relative flex-initial">
              <select className="block appearance-none w-full bg-black border border-white hover:border-gray-500 px-4 py-2 pr-8 shadow leading-tight focus:outline-none focus:shadow-outline text-white">
                <option>English</option>
                <option>French</option>
                <option>Option 3</option>
              </select>
              <div className="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-red-600">
                <svg
                  className="fill-current h-4 w-4"
                  xmlns="http://www.w3.org/2000/svg"
                  viewBox="0 0 20 20"
                >
                  <path d="M9.293 12.95l.707.707L15.657 8l-1.414-1.414L10 10.828 5.757 6.586 4.343 8z" />
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default Copyright;
