import { toast } from 'react-toastify';
import {
  Control,
  useForm,
  FieldErrors,
  SubmitHandler,
  UseFormHandleSubmit,
  UseFormRegister,
} from 'react-hook-form';
import { useState } from 'react';
import { PutClient } from 'services/httpClient/put';

export type ConfigInputs = {
  name: string;
  email: string;
  phone: string;
  old_password: string;
  new_password: string;
};
export interface UseConfigFormProtocols {
  register: UseFormRegister<ConfigInputs>;
  handleSubmit: UseFormHandleSubmit<any>;
  errors: FieldErrors<ConfigInputs>;
  onSubmit: SubmitHandler<ConfigInputs>;
  control: Control<ConfigInputs>;
  isLoading: boolean;
  isValid: boolean;
}

export const useUserConfigForm = (): UseConfigFormProtocols => {
  const [isLoading, setIsLoading] = useState(false);

  const {
    register,
    handleSubmit,
    formState: { errors, isValid },
    control,
  } = useForm<ConfigInputs>({
    mode: 'onBlur',
  });

  const onSubmit: SubmitHandler<ConfigInputs> = async (data, event) => {
    event?.preventDefault();

    setIsLoading(true);

    const postClient = new PutClient();

    const body: ConfigInputs = {
      ...data,
    };

    try {
      const response = await postClient.put({
        url: '/auth/register',
        body,
      });

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