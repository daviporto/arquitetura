import { nextAuthOptions } from 'app/api/auth/[...nextauth]/options';
import { getServerSession } from 'next-auth';
import { redirect } from 'next/navigation';
import { UserEnum, UserProps } from 'protocols/external/user/user';
import { getAuthMe } from 'services/fetch/auth/auth';
import { Config } from 'templates/Config';
import { GetUserRouteConst } from 'utils/routes';
import { HomeUrl } from 'utils/urls';
import type { Metadata } from 'next'
 
export const metadata: Metadata = {
  title: 'FindMe - Configurações',
}

type GetDataProps = {
	data: UserProps;
};

async function getData() {
	const session = await getServerSession(nextAuthOptions);

	if (!session?.access_token) {
		return redirect(`/${HomeUrl}`);
	}

	const { data: authMeResponse } = await getAuthMe(session?.access_token);

	if (authMeResponse.type === UserEnum.RECRUITER) {
		return { data: authMeResponse };
	}

	const userRouteConst = await fetch(
		`${process.env.NEXT_PUBLIC_BACKEND_URL}/${GetUserRouteConst({
			user_id: authMeResponse.id,
			includes: ['competences', 'academicRecords', 'professionalExperiences'],
		})}`,
		{
			headers: { Authorization: `Bearer ${session?.access_token}` },
		},
	);

	if (!userRouteConst.ok) {
		return redirect(`/${HomeUrl}`);
	}

	return userRouteConst.json();
}

export default async function ConfigPage() {
	const { data }: GetDataProps = await getData();

	return <Config {...data} />;
}
