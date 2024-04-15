'use client'
import { redirect } from 'next/navigation';
import { useEffect, useState } from 'react';
import { signIn } from 'next-auth/react';
import { toast } from 'react-toastify';

export type SignInInputs = {
  email: string;
  password: string;
};

import {
  Control,
  useForm,
  FieldErrors,
  SubmitHandler,
  UseFormHandleSubmit,
  UseFormRegister,
} from 'react-hook-form';
import { getSession } from 'next-auth/react';
import { useLoggedUserStore } from 'stores/loggedUserStore';

export interface UseSignInFormProtocols {
  register: UseFormRegister<any>;
  handleSubmit: UseFormHandleSubmit<any>;
  errors: FieldErrors<SignInInputs>;
  onSubmit: SubmitHandler<SignInInputs>;
  control: Control<SignInInputs>;
  isLoading: boolean;
  isValid: boolean;
}

const getUserInfo = async () => {
  return await getSession();
};

export const UseSignInForm = (): UseSignInFormProtocols => {
  const [isLoading, setIsLoading] = useState(false);
  const [isAuthenticated, setIsAuthenticated] = useState(false);
  const { setUser } = useLoggedUserStore((state) => ({
    setUser: state.setUser,
  }));

  const {
    register,
    handleSubmit,
    formState: { errors, isValid },
    control,
  } = useForm<SignInInputs>({
    mode: 'onBlur',
  });

  const onSubmit: SubmitHandler<SignInInputs> = async (data, event) => {
    event?.preventDefault();

    setIsLoading(true);

    const result = await signIn('credentials', {
      email: data.email,
      password: data.password,
      redirect: false,
    });

    const user = await getUserInfo();

    setUser({
      email: user!.email,
    });

    if (result?.error) {
      toast.error(result.error);
      setIsLoading(false);
      return;
    }

    if (result?.status === 200) {
      setIsAuthenticated(!isAuthenticated);
    }

    setIsLoading(false);
  };

  useEffect(() => {
    if (isAuthenticated) {
      const callbackURL = window.location.search;

      if (!callbackURL.includes('callbackurl')) {
        redirect(`/home`);
      }
      const startIndex = callbackURL.indexOf('=') + 2;
      const contentAfterEqual = callbackURL.substring(startIndex);

      redirect(contentAfterEqual);
    }
  }, [isAuthenticated]);

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