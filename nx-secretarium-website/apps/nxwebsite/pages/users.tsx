import Banner from '../components/Banner';
import Nav from '../components/Nav';
import Footer from '../components/Footer';
import Sticky from '../components/Sticky';
import Container from '../components/Container';
import { users } from '../users';
export interface UsersProps {}

export function Users() {
  const showcase = users.map((user) => (
    <a
      href={user.infoLink}
      key={user.infoLink}
      className="flex items-center justify-center"
    >
      <img
        src={user.image}
        alt={user.caption}
        title={user.caption}
        style={user.style}
      />
    </a>
  ));
  return (
    <div className="h-full min-h-full">
      <Banner />
      <Nav />
      <Container>
        <div className="my-12 space-y-12">
          <div className="lg:text-center">
            <p className="text-base leading-6 text-blue-600 font-semibold tracking-wide uppercase">
              Showcase
            </p>
            <h1 className="mt-2 text-3xl leading-8 font-extrabold tracking-tight text-gray-900 sm:text-4xl sm:leading-10">
              Who's using Formik?
            </h1>
            <p className="mt-4 max-w-2xl text-xl leading-7 text-gray-500 lg:mx-auto">
              Formik is the world's most popular form library for React and
              React Native. It's trusted by hundreds of thousands of developers
              in production including teams at Airbnb, Walmart, Stripe, Lyft,
              NASA, US Army and more.
            </p>
          </div>

          <div className="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-16 items-center ">
            {showcase}
          </div>
          <div className="text-center space-y-6 md:space-y-10">
            <div className="mt-4 max-w-2xl text-2xl md:text-5xl leading-7 text-gray-900 font-bold lg:mx-auto">
              Are you using Formik?
            </div>
          </div>
        </div>
      </Container>
      <Footer />
    </div>
  );
}

export default Users;
