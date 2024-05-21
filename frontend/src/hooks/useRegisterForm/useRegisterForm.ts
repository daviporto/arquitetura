import { signIn } from 'next-auth/react';
import { UserAuthRegister } from 'protocols/external/user/user';
import { useState } from 'react';
import {
  Control,
  FieldErrors,
  SubmitHandler,
  UseFormHandleSubmit,
  UseFormRegister,
  useForm,
} from 'react-hook-form';
import { toast } from 'react-toastify';
import { PostClient } from 'services/httpClient/post';
import { useLoggedUserStore } from 'stores/loggedUserStore/loggedUserStore';
import { revertFormatCellphone } from 'utils/formatCellphone';
import { PostAuthRegisterRouteConst } from 'utils/routes';
import { getUserInfo } from 'utils/session';
import { HomeUrl } from 'utils/urls';

export type RegisterInputs = {
	name: string;
	email: string;
	phone: string;
	password: string;
	type: string;
};

export type UseRegisterFormProtocols = {
	register: UseFormRegister<any>;
	handleSubmit: UseFormHandleSubmit<any>;
	errors: FieldErrors<RegisterInputs>;
	onSubmit: SubmitHandler<RegisterInputs>;
	control: Control<RegisterInputs>;
	isLoading: boolean;
	isValid: boolean;
};

export const useRegisterForm = (): UseRegisterFormProtocols => {
	const [isLoading, setIsLoading] = useState(false);

	const {
		register,
		handleSubmit,
		formState: { errors, isValid },
		control,
	} = useForm<RegisterInputs>({
		mode: 'onBlur',
	});

	const { setUser } = useLoggedUserStore((state) => ({
		setUser: state.setUser,
	}));

	const onSubmit: SubmitHandler<RegisterInputs> = async (data, event) => {
		event?.preventDefault();

		setIsLoading(true);

		const postClient = new PostClient();

		const registerBody: UserAuthRegister = {
			name: data.name,
			password: data.password,
			email: data.email,
			phone: revertFormatCellphone(data.phone),
			type: data.type ? 'recruiter' : 'employee',
		};

		try {
			const response = await postClient.post({
				url: `/${PostAuthRegisterRouteConst}`,
				body: {
					...registerBody,
				},
			});

			const result = await signIn('credentials', {
				email: data.email,
				password: data.password,
				redirect: true,
				callbackUrl: `/${HomeUrl}`,
			});

			if (result?.status === 200) {
				const user = await getUserInfo();

				setUser({
					id: user!.id,
					name: user!.name,
					email: user!.email,
					type: user!.type,
				});
			}

			toast.success(response.data.message);
		} catch (error) {
			if (error instanceof Error) {
				return toast.error(error.response.data.message);
			}

			return toast.error('Ocorreu um erro, tente novamente!');
		}

		setIsLoading(false);
	};

	return {
		register,
		handleSubmit,
		errors,
		onSubmit,
		control,
		isValid,
		isLoading,
	};
};
