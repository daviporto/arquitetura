import { NextAuthOptions } from 'next-auth';
import CredentialsProvider from 'next-auth/providers/credentials';
import { UserType } from 'protocols/external/user/user';
import { GetAuthMeRouteConst, PostAuthLoginRouteConst } from 'utils/routes';
import { SignInUrl } from 'utils/urls';

export const nextAuthOptions: NextAuthOptions = {
  session: {
    strategy: 'jwt',
  },
  providers: [
    CredentialsProvider({
      name: 'credentials',
      credentials: {
        email: { label: 'email', type: 'text' },
        password: { label: 'password', type: 'password' },
      },
      async authorize(credentials) {
        try {
          const response = await fetch(
            `${process.env.NEXT_PUBLIC_BACKEND_URL}/${PostAuthLoginRouteConst}`,
            {
              method: 'POST',
              headers: {
                'Content-Type': 'application/json',
              },
              body: JSON.stringify({
                email: credentials?.email,
                password: credentials?.password,
              }),
            },
          );

          if (!response.ok) {
            throw new Error('Nâo foi possível logar, tente novamente!');
          }

          const res = await response.json();

          const authMeResponse = await fetch(
            `${process.env.NEXT_PUBLIC_BACKEND_URL}/${GetAuthMeRouteConst}`,
            {
              headers: {
                Authorization: `Bearer ${res.access_token}`,
              },
            },
          ).then((res) => res.json());

          return {
            id: authMeResponse.data.id,
            name: authMeResponse.data.name,
            email: credentials?.email,
            password: credentials?.password,
            type: authMeResponse.data.type as UserType,
            access_token: res.access_token,
          };
        } catch (error) {
          if (error instanceof Error) {
            return { error: error.message };
          }

          return { error: 'Ocorreu um erro, tente novamente!' };
        }
      },
    }),
  ],
  pages: {
    signIn: `/${SignInUrl}`,
  },
  callbacks: {
    async signIn({ user }) {
      if (user.error) {
        throw new Error(user.error);
      }
      return true;
    },
    async jwt({ token, user }) {
      user && (token.user = user);
      return token;
    },
    async session({ session, token }) {
      session = token.user as any;
      return session;
    },
  },
};
