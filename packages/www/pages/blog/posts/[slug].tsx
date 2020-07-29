import { useRouter } from 'next/router'
import ErrorPage from 'next/error'
import Container from '../../../components/container'
import PostBody from '../../../components/post-body'
import Header from '../../../components/header'
import PostHeader from '../../../components/post-header'
import Layout from '../../../components/layout'
import { getAllPostsWithSlug, getPostAndMorePosts } from '../../../lib/api'
import PostTitle from '../../../components/post-title'
import Head from 'next/head'
import markdownToHtml from '../../../lib/markdownToHtml'
import PostType from '../../../types/post'
import Sticky from 'react-sticky-el'
import NavBar from '../../../components/nav-bar'

type Props = {
    post: PostType
    morePosts: PostType[]
    preview?: boolean
}

const Post = ({ post, morePosts, preview }: Props) => {
    const router = useRouter()
    if (!router.isFallback && !post?.slug) {
        return <ErrorPage statusCode={404} />
    }
    return (
        <Layout preview={preview}>
            <Container>
                <Header />
                {router.isFallback ? (
                    <PostTitle>Loading…</PostTitle>
                ) : (
                        <>
                            <article className="mb-32">
                                <Head>
                                    <title>
                                        {post.title} | Secretarium Blog
                                    </title>
                                    <meta property="og:image" content={post.coverImage.url} />
                                </Head>
                                <PostHeader
                                    title={post.title}
                                    coverImage={post.coverImage.url}
                                    date={post.date}
                                    author={post.author}
                                />
                                <PostBody content={post.content} />
                            </article>
                        </>
                    )}
            </Container>
        </Layout>
    )
}

export default Post

type Params = {
    params: {
        slug: string
    }
}

export async function getStaticProps({ params, preview = null }: { params: any, preview: any }) {
    const data = await getPostAndMorePosts(params.slug, preview)
    const content = await markdownToHtml(data?.posts[0]?.content || '')

    return {
        props: {
            preview,
            post: {
                ...data?.posts[0],
                content,
            },
            morePosts: data?.morePosts,
        },
    }
}


export async function getStaticPaths() {
    const allPosts = await getAllPostsWithSlug()
    return {
        paths: allPosts?.map((post: PostType) => `/blog/posts/${post.slug}`) || [],
        fallback: true,
    }
}