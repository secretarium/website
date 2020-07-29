import Sticky from 'react-sticky-el';
import Container from '../components/container'
import NavBar from '../components/nav-bar'
import Layout from '../components/layout'
import { getAllPostsForHome } from '../lib/api'
import Head from 'next/head'
import Post from '../types/post'

type Props = {
    allPosts: Post[]
}

const Index = ({ allPosts }: Props) => {
    const heroPost = allPosts[0]
    const morePosts = allPosts.slice(1)
    return (
        <>
            <Layout>
                <Head>
                    <title>Secretarium Blog</title>
                </Head>
            </Layout>
        </>
    )
}

export default Index

export async function getStaticProps({ preview = null }) {
    const allPosts = (await getAllPostsForHome(preview)) || []
    return {
        props: { allPosts, preview },
    }
}
