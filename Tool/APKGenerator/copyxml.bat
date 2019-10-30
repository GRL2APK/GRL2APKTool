pushd workflow2code
   for /r %%a in (*.xml) do (
     COPY "%%a" "C:\Users\username\AndroidStudioProjects\RemoteHealthCareUseCase\app\src\main\res\layout"
   )
   popd