import { UserIncludeOption } from 'protocols/external/user/user';

export type GetUserRouteConstProps = {
  user_id: number;
  includes?: UserIncludeOption[];
};

export const GetAuthMeRouteConst = 'auth/me';

export const PostAuthLoginRouteConst = 'auth/login';

export const PostAuthLogOutRouteConst = 'auth/logout';

export const PostAuthVerifyRouteConst = 'auth/verify';

export const PostAuthRegisterRouteConst = 'auth/register';

export const PutUserRouteConst = 'user';

export const GetUsersRouteConst = 'user';

export const PostUserCompetenceRouteConst = 'user/competence';

export const GetUserRouteConst = ({
  user_id,
  includes,
}: GetUserRouteConstProps) => {
  let userRoute = `user/${user_id}`;
  
  if (includes && includes.length) {
    userRoute += `?includes[]=`;
    userRoute += includes.join('&includes[]=');
  }
  
  return userRoute;
};

export const GetJobRouteConst = 'job';