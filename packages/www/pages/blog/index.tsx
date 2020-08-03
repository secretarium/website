import Container from '../../components/container'
import MoreStories from '../../components/more-stories'
import HeroPost from '../../components/hero-post'
import Layout from '../../components/layout'
import { getAllPostsForHome } from '../../lib/api'
import Head from 'next/head'
import Post from '../../types/post'
import PostTitle from '../../components/post-title';

type Props = {
    allPosts: Post[]
}

const Index = ({ allPosts }: Props) => {
    const heroPost = allPosts[0]
    const morePosts = allPosts.slice(1)
    console.log(allPosts);
    return (
        <>
            <Layout>
                <Head>
                    <title>Secretarium Blog</title>
                </Head>
                <Container>
                    <PostTitle>Latest</PostTitle>
                    {heroPost && (
                        <HeroPost
                            title={heroPost.title}
                            coverImage={heroPost.coverImage.url}
                            date={heroPost.date}
                            tags={heroPost.tags}
                            author={heroPost.author}
                            slug={heroPost.slug}
                            excerpt={heroPost.excerpt}
                        />
                    )}
                    {morePosts.length > 0 && <MoreStories posts={morePosts} />}
                </Container>
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
