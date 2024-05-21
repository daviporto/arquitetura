import { keyframes, styled } from 'styled-components'

const skeletonLoading = keyframes`
  0% {
    background-color: hsl(200, 20%, 80%);
  }
  100% {
    background-color: hsl(200, 20%, 95%);
  }
`

export const Skeleton = styled.div`
  width: 100%;
  height: 1.12rem;
  margin-bottom: 0.5rem;
  border-radius: 0.25rem;
  background-image: linear-gradient(
    100deg,
    rgba(255, 255, 255, 0),
    rgba(255, 255, 255, 0.5) 60%,
    rgba(255, 255, 255, 0) 80%
  );
  animation: ${skeletonLoading} 1s infinite alternate;
`
