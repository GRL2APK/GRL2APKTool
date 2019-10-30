pushd workflow2code
   for /r %%a in (*.java) do (
     COPY "%%a" "C:\Users\%username%\AndroidStudioProjects\RemoteHealthCareUseCase\app\src\main\java\com\example\username\remotehealthcareusecase"
   )
   popd