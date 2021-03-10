import React from 'react';
import ListItem from './shared/ListItem';

const Benefits = () => {
  return (
    <section className="bg-white body-font">
      <div className="py-24 mx-auto container">
        <div className="grid grid-cols-3 gap-4">
          <div className="pb-16 col-span-2 ...">
            <h3 className="text-3xl mx-auto leading-tight font-extrabold tracking-tight sm:text-4xl  lg:leading-none mt-2 text-red-600">
              Say hello to total data control
            </h3>
            <p className="mt-4 text-xl leading-7 text-gray-700 pr-15 md:max-w-xs lg:max-w-md xl:max-w-2xl">
              We believe everyone has the right to control their own data,
              people and businesses. The ability to protect sensitive
              information during processing opens the door to endless commercial
              data opportunities, without compromising individual privacy.
            </p>
          </div>
          <div className="...">
            <div className="grid grid-flow-row grid-cols-1 sm:grid-cols-2 gap-5 text-gray-700 max-w-screen-lg mx-auto text-lg">
              <ListItem number="01" initial_snippet="Lets users keep" bold_snippet="control" last_snippet="of their data"/>
              <ListItem number="02" initial_snippet="" bold_snippet="Secures" last_snippet="commercial data"/>
              <ListItem number="03" initial_snippet="Powers secure" bold_snippet="monetisation" last_snippet=""/>
              <ListItem number="04" initial_snippet="Enables secure" bold_snippet="collaboration" last_snippet=""/>
              <ListItem number="05" initial_snippet="" bold_snippet="Protects" last_snippet="anonymity"/>
            </div>
          </div>
        </div>
      </div>
    </section>
  );
};

export default Benefits;
